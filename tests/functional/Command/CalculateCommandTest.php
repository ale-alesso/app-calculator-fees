<?php

namespace App\Tests\Functional\Command;

use App\Command\CalculateCommand;
use App\Service\Converter\Exchange\CurrencyExchangeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\ConsoleOutput;

class CalculateCommandTest extends KernelTestCase
{
    private CalculateCommand $command;

    /**
     * @var CurrencyExchangeInterface|MockObject
     */
    private CurrencyExchangeInterface $exchange;

    protected function setUp(): void
    {
	self::bootKernel();

	$this->exchange = $this->createMock(CurrencyExchangeInterface::class);
	self::$container->set(CurrencyExchangeInterface::class, $this->exchange);

	$this->command = self::$container->get('app-fees-calculator.command.calculate');
    }

    /**
     * @param array $rates
     * @param string $source
     * @param string $expected
     *
     * @dataProvider executeOkDataProvider
     */
    public function testExecuteOk(array $rates, string $source, string $expected)
    {
	foreach ($rates as $index => $rate) {
	    $this->exchange->expects($this->at($index))
		->method('getRate')
		->willReturn($rate);
	}

	$this->executeCommand($source);
	$actual = file_get_contents(self::$container->getParameter('data_file_writer'));

	$this->assertEquals($expected, $actual);
    }

    public function executeOkDataProvider(): array
    {
	return [
	    'csv' => [
		'rates' => [
		    130.188335,
		    0.0076811797308876,
		    1.20377,
		    1.20377,
		    1.20377,
		    130.188335,
		],
		'source' => $this->getFixtureSource('input.csv'),
		'expected' => file_get_contents($this->getFixtureSource('output.txt')),
	    ],
	];
    }

    private function executeCommand(string $source)
    {
	$inputDefinition = new InputDefinition([new InputArgument('source')]);
	$input = new ArgvInput([$this->command->getName(), $source], $inputDefinition);

	$output = new ConsoleOutput();
	return $this->command->execute($input, $output);
    }

    private function getFixtureSource(string $source): string
    {
	return realpath(__DIR__ . '/../../fixtures/' . $source);
    }
}
