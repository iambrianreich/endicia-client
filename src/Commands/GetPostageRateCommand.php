<?php

/**
 * This file contains the RWC\Endicia\Commands\GetPostageRateCommand class.
 * @package RWC\Endicia\Commands
 */

namespace RWC\Endicia\Commands;

use RWC\Endicia\MailpieceDimensions;
use RWC\Endicia\PostageRateRequest;
use RWC\Endicia\ResponseOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\Client as EndiciaClient;

class GetPostageRateCommand extends Command
{
    /**
     * Configures the GetPostageRateCommand.
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('endicia:postage-rate')
            ->setDescription(
                'Returns a postage rate given a set of parameters.'
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
                'mailclass',
                null,
                InputOption::VALUE_REQUIRED,
                'The shape of the mailpiece. See Endicia docs or RWC\Endicia\MailClass for applicable shapes.'
            )
            ->addOption(
                'weight',
                null,
                InputOption::VALUE_REQUIRED,
                'The weight of the mailpiece in ounces, rounded to one decimal.'
            )
            ->addOption(
                'frompostalcode',
                null,
                InputOption::VALUE_REQUIRED,
                'The postal code the mailpiece will be sent from.'
            )
            ->addOption(
                'topostalcode',
                null,
                InputOption::VALUE_REQUIRED,
                'The postal code the mailpiece will be sent to.'
            )
            ->addOption(
                'fromcountrycode',
                null,
                InputOption::VALUE_OPTIONAL,
                'The country code the mailpiece will be sent from. ' .
                'Optional if sending domestic in the US.'
            )
            ->addOption(
                'tocountrycode',
                null,
                InputOption::VALUE_OPTIONAL,
                'The country code the mailpiece will be sent to. ' .
                'Optional if sending domestic in the US.'
            )
            ->addOption(
                'mailshape',
                null,
                InputOption::VALUE_OPTIONAL,
                'The shape of the mailpiece. See Endicia docs or RWC\Endicia\MailShape for applicable shapes.'
            )
            ->addOption(
                'dimensions',
                null,
                InputOption::VALUE_OPTIONAL,
                'A comma-separated list of the dimensions of the mailpiece in inches, rounded to one decimal. ' .
                'It must be formatted as follows: "LENGTH,WIDTH,HEIGHT"'
            )
            ->addOption(
                'pricing',
                null,
                InputOption::VALUE_OPTIONAL,
                'The pricing option to use for the rate.' .
                'If this element is not supplied, pricing will be based on the MailClass ' .
                'and any qualified discounts available to AccountID.' .
                'Must be set to "CommercialBase", "CommercialPlus", or "Retail".'
            )
//            ->addOption(
//                'services',
//                null,
//                InputOption::VALUE_OPTIONAL,
//                'A comma-separated list of key-value pairs to enable specific services. ' .
//                'Refer to the Endicia docs for a list of available Services. ' .
//                'The list must be formatted as "KEY1=VALUE1,KEY2=VALUE2".'
//            )
            ->addOption(
                'servicelevel',
                null,
                InputOption::VALUE_OPTIONAL,
                'Enables Next Day or Second Day Post Office to Addressee Service. ' .
                'Applies only to Priority Mail Express mailpieces.' .
                'Set to "NextDay2ndDayPOToAddressee" to use this feature.'
            )
            ->addOption(
                'sundayholidaydelivery',
                null,
                InputOption::VALUE_OPTIONAL,
                'Sets the Sunday/Holiday delivery options. Only for Priority Mail Express mailpieces. ' .
                'Set to "SUNDAY" for Sunday delivery, "HOLIDAY" for holidays only, "TRUE" for both, or "FALSE" for neither.'
            )
            ->addOption(
                'shipdate',
                null,
                InputOption::VALUE_OPTIONAL,
                'The ship date in MM/DD/YYYY format. ' .
                'Required for Priority Mail Express Sunday and Holiday delivery service.'
            )
            ->addOption(
                'shiptime',
                null,
                InputOption::VALUE_OPTIONAL,
                'The ship time in HH:MM AM or HH:MM PM format. ' .
                'Applies only to Priority Mail Express Sunday and Holiday delivery. Defaults to 12:01AM'
            )
            ->addOption(
                'dateadvance',
                null,
                InputOption::VALUE_OPTIONAL,
                'The number of days to advance date the rate. ' .
                'It must be a number between 0-7.'
            )
            ->addOption(
                'deliverytimedays',
                null,
                InputOption::VALUE_OPTIONAL,
                'Set to true to include the amount of days for delivery in the response.'
            )
            ->addOption(
                'estimateddeliverydate',
                null,
                InputOption::VALUE_OPTIONAL,
                'Set to true to include the estimated delivery date in the response. ' .
                'This option is dependant on deliverytimedays being set to true'
            )
            ->addOption(
                'automationrate',
                null,
                InputOption::VALUE_OPTIONAL,
                'Set to true to use the automation rate for valid mail pieces. ' .
                'Applicable only to letter-shape mailpieces using First-Class'
            )
            ->addOption(
                'machinable',
                null,
                InputOption::VALUE_OPTIONAL,
                'Indicates whether the parcel is machinable. ' .
                'Set to false for non-machinable parcels. ' .
                'Parcels over 35lbs are automatically marked non-machinable.'
            )
            ->addOption(
                'packagetypeindicator',
                null,
                InputOption::VALUE_OPTIONAL,
                'Set to "Softpack" to indicate Commercial Plus cubic price ' .
                'for soft-pack packaging material'
            )
            ->addOption(
                'postageprice',
                null,
                InputOption::VALUE_OPTIONAL,
                'Whether or not to include the PostagePrice Node in the response.' .
                'Set to true to include a ResponseOptions Node in the request and return the PostagePrice Node.'
            );
    }

    /**
     * Executes the PostageRate request against the Endicia API.
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

        $output->writeln('Sending postage rate request...');

        // Create credential.
        $ci = (! empty($token)) ?
            CertifiedIntermediary::createFromToken($token) :
            CertifiedIntermediary::createFromCredentials($accountId, $passphrase);

        // TODO: Implement Services Node properly
        $request = new PostageRateRequest($requesterId, $ci, $mailClass, $weight, $fromPostalCode, $toPostalCode, $fromCountryCode, $toCountryCode, $mailShape, $dimensions, $pricing, null, $serviceLevel, $sundayHolidayDelivery, $shipDate, $shipTime, $dateAdvance, $deliveryTimeDays, $estimatedDeliveryDate, $automationRate, $machinable, $packageTypeIndicator, $responseOptions);
        $client  = new EndiciaClient($mode);
        $result  = $client->postageRateRequest($request);

        if ($result->isSuccessful()) {
            $output->writeln('Postage rate request successful! You can view the data below:');
            if ($result->getPostagePrice() != null) {
                $output->write(var_dump($result->getPostagePrice()));
            } else {
                $output->write(var_dump($result->getPostage()));
            }

            return;
        }

        // Failed
        $output->writeln(sprintf(
            'Postage rate request failed: ' . $result->getErrorMessage()
        ));
    }

    protected function getOptions(InputInterface $input)
    {
        $options                          = [];
        $options['mode']                  = $input->getOption('mode');
        $options['requesterId']           = $input->getOption('requesterId');
        $options['token']                 = $input->getOption('token');
        $options['accountId']             = $input->getOption('accountId');
        $options['passphrase']            = $input->getOption('passphrase');
        $options['mailClass']             = $input->getOption('mailclass');
        $options['weight']                = $input->getOption('weight');
        $options['fromPostalCode']        = $input->getOption('frompostalcode');
        $options['toPostalCode']          = $input->getOption('topostalcode');
        $options['fromCountryCode']       = $input->getOption('fromcountrycode');
        $options['toCountryCode']         = $input->getOption('tocountrycode');
        $options['mailShape']             = $input->getOption('mailshape');
        $options['dimensions']            = $input->getOption('dimensions');
        $options['pricing']               = $input->getOption('pricing');
        // TODO: Implement Services Node properly
        //$options['services']              = $input->getOption('services');
        $options['serviceLevel']          = $input->getOption('servicelevel');
        $options['sundayHolidayDelivery'] = $input->getOption('sundayholidaydelivery');
        $options['shipDate']              = $input->getOption('shipdate');
        $options['shipTime']              = $input->getOption('shiptime');
        $options['dateAdvance']           = $input->getOption('dateadvance');
        $options['deliveryTimeDays']      = $input->getOption('deliverytimedays');
        $options['estimatedDeliveryDate'] = $input->getOption('estimateddeliverydate');
        $options['automationRate']        = $input->getOption('automationrate');
        $options['machinable']            = $input->getOption('machinable');
        $options['packageTypeIndicator']  = $input->getOption('packagetypeindicator');
        $options['responseOptions']       = $input->getOption('postageprice');

        // Override requesterId for sandbox
        if ($options['mode'] == 'sandbox') {
            $options['requesterId'] = 'lxxx';
        }

        // Build MailpieceDimensions Node from input
        if ($options['dimensions'] != null) {
            $lwh = explode(',', $options['dimensions']);
            $options['dimensions'] = new MailpieceDimensions($lwh[0], $lwh[1], $lwh[2]);
        }

        // Build ResponseOptions Node
        if ($options['responseOptions'] != null) {
            $options['responseOptions'] = new ResponseOptions(true);
        }

        $this->validateOptions($options);

        return $options;
    }

    protected function validateOptions(array $options)
    {
        extract($options);

        $errors = [];

        if (!in_array($mode, ['sandbox', 'production'])) {
            $errors[] = 'mode must be either "sandbox" or "production"';
        }

        if (empty($mode)) {
            $errors[] = 'mode option is required';
        }

        if ($mode != 'sandbox' && empty($requesterId)) {
            $errors[] = 'A requester id is required in production mode';
        }

        if (empty($token) && (empty($accountId) || empty($passphrase))) {
            $errors[] = 'Either a "token" or the set of "requesterId" ' .
                'and "passphrase" are required.';
        }

        if ($dimensions != null && !preg_match('/\d*\.\d,\d*\.\d,\d*\.\d/g', $dimensions)) {
            $errors[] = 'Dimensions do not match LENGTH,WIDTH,HEIGHT format.';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException($errors[0]);
        }
    }
}