<?php
namespace Utils\InputFilter;

use Utils\InputFilter\Exception\InvalidDataException;
use Zend\InputFilter\InputFilterInterface;

class InputFilterUtils {
    public static function flattenMessages(array $messages) {
        $flatMessages = array();
        $format = "Invalid field '%s': %s [%s]";
        foreach ($messages as $field => $codeAndMessage) {
            foreach ($codeAndMessage as $code => $message) {
                $flatMessage = sprintf($format, $field, $message, $code);
                $flatMessages[] = $flatMessage;
            }
        }
        return $flatMessages;
    }

    public static function assertDataIsValid(InputFilterInterface $inputFilter, $data) {
        $inputFilter->setData($data);
        if (!$inputFilter->isValid()) {
            $messages = $inputFilter->getMessages();
            $messages = static::flattenMessages($messages);
            $message = implode("\n", $messages);
            throw new InvalidDataException($message);
        }
    }
}