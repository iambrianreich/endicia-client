<?php

/**
 * This file contains RWC\Endicia\Commands\ChangePassPhraseCommand class
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  Copyright (C) 2018 Reich Web Consulting
 * @package    Catalyst\Customers
 * @subpackage Commands
 * @license    Private Use
 * @link       https://www.reich-consulting.net/projects/endicia-client/
 */

namespace RWC\Endicia\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\ChangePassphraseRequest;
use RWC\Endicia\Client as EndiciaClient;

/**
 * Console command for ChangePassPhraseCommand.
 *
 * This command allows users to change their Endicia PassPhrase via the command
 * line. The command required several options. The "mode" option specifies
 * whether to run in production or sandbox mode. This is a required option so
 * you are always explicitly stating which version of the API you want to use.
 *
 * The requesterId option specifies that id of your application which has been
 * assigned by Endicia. This is only required if you are using production mode:
 * the requester id is automatically assigned for sandbox mode.
 *
 * The token, accountId, and passphrase options specify your authentication
 * credentials. Either a token, or both accountId and passphrase, are required.
 *
 * The newpassphrase option specifies the new passphrase to reset the account.
 *
 * The requesttoken option (set to either "true" or "false") specifies whether
 * or not to request a security token, which can be used later in place of
 * account id and password credentials. The default is false.
 *
 * The command will execute the password change request against the Endicia
 * API and output the response.
 *
 * @author     Brian Reich <breich@reich-consulting.net>
 * @copyright  2017 Catalyst Fabric Solitions
 * @license    Private Use
 */
class ChangePassPhraseCommand extends Command
{
    /**
     * Configures the EnableNexioCommand.
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('endicia:change-passphrase')
            ->setDescription(
                'Change the passphrase of an Endicia account.'
            )
            
            ->addOption(
                'mode',
                null,
                InputOption::VALUE_REQUIRED,
                'The mode of execution (sandbox or production).'
            )
            ->addOption(
                'requesterId',
                null,
                InputOption::VALUE_REQUIRED,
                'The requester id identifies the app to Endicia.'
            )
            ->addOption(
                'token',
                null,
                InputOption::VALUE_OPTIONAL,
                'A security token that authenticates the account. Either a ' .
                    'token or an accountId and passphrase must be provided.'
            )
            ->addOption(
                'accountId',
                null,
                InputOption::VALUE_OPTIONAL,
                'The account id. Either both the accountId and passphrase, ' .
                    'or a token must be provided.'
            )
            ->addOption(
                'passphrase',
                null,
                InputOption::VALUE_OPTIONAL,
                'The current passphrase of the account. Either both the ' .
                    'accountId and passphrase, or a token must be provided.'
            )
            ->addOption(
                'newpassphrase',
                null,
                InputOption::VALUE_REQUIRED,
                'The new passphrase to assign to the account.'
            )
            ->addOption(
                'requesttoken',
                null,
                InputOption::VALUE_OPTIONAL,
                'True to request a security token that can be used in place of credentials in future requests.',
                false
            );
    }
    
    /**
     * Executes the ChangePassPhrase request against the Endicia API.
     *
     * The execute method first collects all of the options to the command. If
     * any options are invalid an \InvalidArgumentException is thrown, which is
     * displayed to the user before closing. If all options are valid the
     * request is created and executed. On success the user is shown their new
     * passphrase and token if one was requested.
     *
     * On failure, the error message from the Endicia API is displayed.
     *
     * @param  InputInterface  $input  The input interface.
     * @param  OutputInterface $output The output interface.
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $this->getOptions($input);

        extract($options);
        
        $output->writeln(sprintf(
            'Attempting to change passphrase to "%s"' . ($requestToken ?
                ', and requesting a token' : ''),
            $newPassphrase
        ));

        // Create credential.
        $ci = (! empty($token)) ?
            CertifiedIntermediary::createFromToken($token) :
            CertifiedIntermediary::createFromCredentials($accountId, $passphrase);

        $request = new ChangePassPhraseRequest($requesterId, $ci, $newPassphrase, $requestToken);
        $client  = new EndiciaClient($mode);
        $result  = $client->changePassPhrase($request);

        if ($result->isSuccessful()) {
            $output->writeln('Passphrase changed successfuly.  Your new ' .
                'passphrase is ' . $newPassphrase);

            // If a token was requested, show it.
            if ($requestToken) {
                $output->writeln(
                    'Your security token is ' .
                    $result->getToken()
                );
            }

            return;
        }

        // Failed
        $output->writeln(sprintf(
            'Password change request failed: ' . $result->getErrorMessage()
        ));
    }

    protected function getOptions(InputInterface $input)
    {
        $options                  = [];
        $options['mode']          = $input->getOption('mode');
        $options['requesterId']   = $input->getOption('requesterId');
        $options['token']         = $input->getOption('token');
        $options['accountId']     = $input->getOption('accountId');
        $options['passphrase']    = $input->getOption('passphrase');
        $options['newPassphrase'] = $input->getOption('newpassphrase');
        
        $options['requestToken']  =
            $input->getOption('requesttoken') === 'true' ? true : false;

        // Override requesterId for sandbox
        if ($options['mode'] == 'sandbox') {
            $options['requesterId'] = 'lxxx';
        }

        $this->validateOptions($options);

        return $options;
    }

    protected function validateOptions(array $options)
    {
        extract($options);

        $errors = [];

        if (! in_array($mode, ['sandbox', 'production'])) {
            $errors[] = 'mode must be either "sandbox" or "production"';
        }

        if (empty($mode)) {
            $errors[] = 'mode option is required';
        }

        if ($mode != 'testing' && empty($requesterId)) {
            $errors[] = 'A requester id is required in production mode';
        }

        if (empty($token) && (empty($accountId) || empty($passphrase))) {
            $errors[] = 'Either a "token" or the set of "requesterId" ' .
                'and "passphrase" are required.';
        }

        if (empty($newPassphrase)) {
            $errors[] = 'A new passphrase is required';
        }

        if (! empty($errors)) {
            throw new \InvalidArgumentException($errors[0]);
        }
    }
}
