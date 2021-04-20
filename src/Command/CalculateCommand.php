<?php

namespace App\Command;

use App\Service\Data\DataReaderInterface;
use App\Service\Data\DataWriterInterface;
use App\Validator\TransactionValidatorInterface;
use Symfony\Component\Console\Command\Command;
use App\Service\Calculator\CalculatorInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateCommand extends Command
{
    private DataReaderInterface $reader;
    private CalculatorInterface $calculator;
    private DataWriterInterface $writer;
    private TransactionValidatorInterface $transactionValidator;

    public function __construct(
	DataReaderInterface $reader,
	CalculatorInterface $calculator,
	DataWriterInterface $writer,
	TransactionValidatorInterface $transactionValidator
    ) {
	parent::__construct();

	$this->reader = $reader;
	$this->calculator = $calculator;
	$this->writer = $writer;
	$this->transactionValidator = $transactionValidator;
    }

    protected function configure(): void
    {
	$this->setName('app:calculate')
	    ->setDescription('Calculate fees')
	    ->addArgument('source', InputArgument::REQUIRED, 'Source');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
	$source = $input->getArgument('source');

	foreach ($this->reader->read($source) as $transaction) {
	    $this->transactionValidator->validate($transaction);
	    $commission = $this->calculator->calculate($transaction);
	    $this->writer->write($commission->getFormattedAmount());
	}

	$this->writer->close();

	return 0;
    }
}
