<?php

namespace Laradic\Generators\Laravel\EloquentModel\Command;

use Illuminate\Console\Command;
use Illuminate\Config\Repository as AppConfig;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Laradic\Generators\Laravel\EloquentModel\Config;
use Laradic\Generators\Laravel\EloquentModel\Generator;

/**
 * Class GenerateElementCommand
 * @package Krlove\EloquentModelGenerator\Command
 */
class GenerateModelCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'generate:model';

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @var AppConfig
     */
    protected $appConfig;

    /**
     * GenerateElementCommand constructor.
     * @param Generator $generator
     * @param AppConfig $appConfig
     */
    public function __construct(Generator $generator, AppConfig $appConfig)
    {
        parent::__construct();

        $this->generator = $generator;
        $this->appConfig = $appConfig;
    }

    /**
     * Executes the command
     */
    public function fire()
    {
        $config = $this->createConfig();

        $model = $this->generator->generateElement($config);

        $this->output->writeln(sprintf('Model %s generated', $model->getName()->getName()));
    }

    /**
     * Add support for Laravel 5.5
     */
    public function handle()
    {
        $this->fire();
    }

    /**
     * @return Config
     */
    protected function createConfig()
    {
        $config = [];

        foreach ($this->getArguments() as $argument) {
            $config[$argument[0]] = $this->argument($argument[0]);
        }
        foreach ($this->getOptions() as $option) {
            $value = $this->option($option[0]);
            if ($option[2] == InputOption::VALUE_NONE && $value === false) {
                $value = null;
            }
            $config[$option[0]] = $value;
        }

        $config['db_types'] = $this->appConfig->get('eloquent_model_generator.db_types');

        return new Config($config, $this->appConfig->get('eloquent_model_generator.model_defaults'));
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['class-name', InputArgument::REQUIRED, 'Model class name'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['table-name', 'tn', InputOption::VALUE_OPTIONAL, 'Name of the table to use', null],
            ['output-path', 'op', InputOption::VALUE_OPTIONAL, 'Directory to store generated Element', null],
            ['namespace', 'ns', InputOption::VALUE_OPTIONAL, 'Namespace of the Element', null],
            ['base-class-name', 'bc', InputOption::VALUE_OPTIONAL, 'Model parent class', null],
            ['no-timestamps', 'ts', InputOption::VALUE_NONE, 'Set timestamps property to false', null],
            ['date-format', 'df', InputOption::VALUE_OPTIONAL, 'dateFormat property', null],
            ['connection', 'cn', InputOption::VALUE_OPTIONAL, 'Connection property', null],
            ['backup', 'b', InputOption::VALUE_NONE, 'Backup existing Element', null]
        ];
    }
}
