<?php


namespace DansMaCulotte\Monetico\Resources;

use DansMaCulotte\Monetico\Exceptions\Exception;

class Ressource
{
    /** @var array */
    protected $parameters = [];

    /** @var array */
    protected $keys = [];

    /**
     * Ressource constructor.
     * @param array $fields
     * @throws Exception
     */
    public function __construct(array $fields = [])
    {
        $this->setParameters($fields);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function getParameter(string $name)
    {
        if (!in_array($name, $this->keys, true)) {
            throw Exception::invalidResourceParameter($name);
        }

        if (isset($this->parameters[$name]) === false) {
            return null;
        }

        return $this->parameters[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function setParameter(string $name, $value = null): void
    {
        if (!in_array($name, $this->keys, true)) {
            throw Exception::invalidResourceParameter($name);
        }

        $this->parameters[$name] = $value;
    }

    /**
     * @param array $fields
     * @throws Exception
     */
    public function setParameters(array $fields): void
    {
        foreach ($fields as $fieldKey => $fieldValue) {
            $this->setParameter($fieldKey, $fieldValue);
        }
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
