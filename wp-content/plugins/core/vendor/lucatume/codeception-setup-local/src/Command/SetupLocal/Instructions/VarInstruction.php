<?php
namespace tad\Codeception\Command\SetupLocal\Instructions;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class VarInstruction extends AbstractInstruction implements InstructionInterface
{
    protected $validations = [
        'int' => FILTER_VALIDATE_INT,
        'float' => FILTER_VALIDATE_FLOAT,
        'bool' => FILTER_VALIDATE_BOOLEAN,
        'url' => FILTER_VALIDATE_URL,
        'email' => FILTER_VALIDATE_EMAIL,
        'regexp' => FILTER_VALIDATE_REGEXP
    ];

    protected $validationArgs = [];

    protected $normalizations = [];

    public function __construct($value, array $vars, InputInterface $input, OutputInterface $output, $helper)
    {
        $this->normalizations = [
            'yesno' => [$this, 'normalizeYesNo']
        ];

        parent::__construct($value, $vars, $input, $output, $helper);
    }

    public function execute()
    {
        if (!isset($this->value['name'])) {
            throw new RuntimeException('"name" argument is required in the "var" instruction');
        }

        if (isset($this->value['value'])) {
            $this->vars[$this->value['name']] = $this->replaceVarsInString($this->value['value']);
            return $this->vars;
        }

        if (!isset($this->value['question'])) {
            throw new RuntimeException('"question" is required in the "var" instruction');
        }

        $go = $this->getGo();

        if (!$go) {
            return $this->vars;
        }

        $default = isset($this->value['default']) ? $this->value['default'] : '';
        $defaultMessage = $default ? ' (' . $default . ')' : '';
        $questionText = $this->value['question'] . $defaultMessage;

        $question = null;

        if (isset($this->value['validate']) && $this->value['validate'] === 'yesno') {
            $question = new ConfirmationQuestion($questionText);
        } else {
            $question = new Question($questionText, $default);
        }

        if (isset($this->value['validate'])) {
            if ($this->value['validate'] === 'regexp') {
                $this->validationArgs['regexp'] = ['options' => ['regexp' => $this->value['regexp']]];
            }

            $question->setValidator(function ($answer) {
                return $this->validate($answer);
            });

            $question->setMaxAttempts(3);
        }
        $answer = $this->helper->ask($this->input, $this->output, $question);
        $this->vars[$this->value['name']] = trim($this->normalizeVar($answer));

        return $this->vars;
    }

    protected function normalizeYesNo($value)
    {
        return empty($value) ? 'no' : 'yes';
    }

    protected function validate($answer)
    {

        if (!(is_array($this->value) && isset($this->value['validate']) && isset($this->validations[$this->value['validate']]))) {
            return $answer;
        }

        $validationArg = isset($this->validationArgs[$this->value['validate']]) ? $this->validationArgs[$this->value['validate']] : null;

	    $valid = ! empty($validationArg) ?
		    filter_var($answer, $this->validations[$this->value['validate']], $validationArg) : filter_var($answer, $this->validations[$this->value['validate']]);

	    if (! $valid) {
            throw new InvalidArgumentException('[' . $answer . '] is not a valid answer .');
        }

        return $answer;
    }

    protected function normalizeVar($value)
    {
        return is_array($this->value)
        && isset($this->value['validate'])
        && isset($this->normalizations[$this->value['validate']]) ?
            call_user_func($this->normalizations[$this->value['validate']], $value)
            : $value;
    }
}