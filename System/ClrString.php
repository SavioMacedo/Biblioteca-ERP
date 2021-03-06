<?php

/**
 *  LINQ concept for PHP
 *  Copyright (C) 2015  Marcel Joachim Kloubert <marcel.kloubert@gmx.net>
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 3.0 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library.
 */


namespace System;

use \System\Linq\Enumerable;


/**
 * A constant string.
 *
 * @package System
 * @author Marcel Joachim Kloubert <marcel.kloubert@gmx.net>
 */
class ClrString extends \System\ObjectWrapper implements \ArrayAccess, \Countable, IComparable, \IteratorAggregate, \Serializable {
    /**
     * Value for an index that tells that a string was not found.
     */
    const NOT_FOUND_INDEX = -1;


    /**
     * Initializes a new instance of that class.
     *
     * @param string $value The value.
     */
    public function __construct($value = null) {
        parent::__construct(static::valueToString($value));
    }

    /**
     * Handles that string as format string and returns it as formatted string.
     *
     * @param mixed ...$arg One or more argument for that string.
     *
     * @return string The formatted string.
     */
    public function __invoke() {
        return static::formatArray($this->getWrappedValue(),
                                   \func_get_args());
    }


    /**
     * Returns that string as mutable StringBuilder.
     *
     * @return \System\Text\StringBuilder The converted instance.
     */
    public function asMutable() {
        return new \System\Text\StringBuilder($this->getWrappedValue());
    }

    /**
     * Returns a value as a String object.
     *
     * @param mixed $val The value to convert/cast.
     * @param bool $nullAsEmpty If (true) an empty string will be returned instead of a (null) reference.
     *
     * @return static
     */
    public static function asString($val, $nullAsEmpty = true) {
        if (null === $val) {
            if ($nullAsEmpty) {
                $val = '';
            }
        }

        if (null === $val) {
            return null;
        }

        if ($val instanceof static) {
            return $val;
        }

        return new static($val);
    }

    /**
     * {@inheritDoc}
     */
    public function compareTo($other) {
        return \strcmp($this->getWrappedValue(),
                       static::getRealValue($other));
    }

    /**
     * Concats items to one string.
     *
     * @param mixed $items The items to join.
     * @param string $defValue The default value to return if item list is empty
     *
     * @return string The joined string.
     */
    public static function concat($items, $defValue = '') {
        return static::join('', $items, $defValue);
    }

    /**
     * Checks if that string contains an expression.
     *
     * @param string $expr The expression to search for.
     * @param bool $ignoreCase Ignore case or not.
     *
     * @return bool String contains expression or not.
     */
    public function contains($expr, $ignoreCase = false) {
        return false !== $this->invokeFindStringFunc($expr, $ignoreCase);
    }

    /**
     * Creates a string that is stored in a specific encoding (s. \iconv()).
     *
     * @param mixed $str The string to convert.
     * @param string $srcEnc The source encoding. If not defined the input encoding is used.
     * @param string $targetEnc The target encoding. If not defined the internal encoding is used.
     *
     * @return static
     */
    public static function convertFrom($str, $srcEnc = null, $targetEnc = null) {
        if (static::isNullOrWhitespace($srcEnc)) {
            $srcEnc = \iconv_get_encoding('input_encoding');
        }

        if (static::isNullOrWhitespace($targetEnc)) {
            $targetEnc = \iconv_get_encoding('internal_encoding');
        }

        return new static(\iconv($srcEnc, $targetEnc,
                                 static::valueToString($str)));
    }

    /**
     * Converts a string to a new encoding (s. \iconv()).
     *
     * @param string $targetEnc The target encoding. If not defined the output encoding is used.
     * @param string $srcEnc The source encoding. If not defined the internal encoding is used.
     *
     * @return static
     */
    public function convertTo($targetEnc = null, $srcEnc = null) {
        if (static::isNullOrWhitespace($targetEnc)) {
            $targetEnc = \iconv_get_encoding('output_encoding');
        }

        if (static::isNullOrWhitespace($srcEnc)) {
            $srcEnc = \iconv_get_encoding('internal_encoding');
        }

        return new static(\iconv($srcEnc, $targetEnc,
                                 $this->getWrappedValue()));
    }

    /**
     * {@inheritDoc}
     */
    public function count() {
        return \strlen($this->getWrappedValue());
    }

    /**
     * Checks if that strings ends with a specific expression.
     *
     * @param string $expr The expression to check.
     * @param bool $ignoreCase Ignore case or not.
     *
     * @return bool Ends with expression or not.
     */
    public function endsWith($expr, $ignoreCase = false) {
        $expr = static::valueToString($expr);

        return ('' === $expr) ||
               (($temp = $this->length() - \strlen($expr)) >= 0 &&
                false !== $this->invokeFindStringFunc($expr, $ignoreCase, $temp));
    }

    /**
     * {@inheritDoc}
     */
    public function equals($other) {
        $other = static::getRealValue($other);

        if (\is_scalar($other)) {
            return $this->getWrappedValue() === static::valueToString($other);
        }

        return $this === $other;
    }

    /**
     * Formats a string.
     *
     * @param string $format The format string.
     * @param mixed ...$arg One or more argument for $format.
     *
     * @return string The formatted string.
     */
    public static function format($format) {
        return static::formatArray($format,
                                   \array_slice(\func_get_args(), 1));
    }

    /**
     * Formats a string.
     *
     * @param string $format The format string.
     * @param mixed $args One or more argument for $format.
     *
     * @return string The formatted string.
     */
    public static function formatArray($format, $args = null) {
        if (!\is_array($args)) {
            $args = Enumerable::create($args)
                              ->toArray();
        }

        return \preg_replace_callback('/{(\d+)(\:[^}]*)?}/i',
                                      function($match) use ($args) {
                                          $i = (int)$match[1];

                                          $format = null;
                                          if (isset($match[2])) {
                                              $format = \substr($match[2], 1);
                                          }

                                          return \array_key_exists($i, $args) ? ClrString::parseFormatStringValue($format, $args[$i])
                                                                              : $match[0];
                                      }, $format);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator() {
        return Enumerable::create($this->getWrappedValue());
    }

    /**
     * Finds the first occurrence of a string expression.
     *
     * @param string $expr The string to search for.
     * @param bool $ignoreCase Ignore case or not.
     * @param int $offset The offset.
     *
     * @return int The zero based index or -1 if not found.
     */
    public function indexOf($expr, $ignoreCase = false, $offset = 0) {
        $result = $this->invokeFindStringFunc($expr, $ignoreCase, $offset);

        return false !== $result ? $result
                                 : static::NOT_FOUND_INDEX;
    }

    /**
     * Finds the first occurrence of a char list.
     *
     * @param string $chars The list of chars.
     * @param bool $ignoreCase Ignore case or not.
     * @param int $offset The offset.
     *
     * @return int The zero based index or -1 if not found.
     */
    public function indexOfAny($chars, $ignoreCase = false, $offset = 0) {
        $chars = static::valueToString($chars);
        $charCount = \strlen($chars);

        $result = static::NOT_FOUND_INDEX;

        for ($i = 0; $i < $charCount; $i++) {
            $result = $this->indexOf($chars[$i], $ignoreCase, $offset);

            if (static::NOT_FOUND_INDEX != $result) {
                // found
                break;
            }
        }

        return $result;
    }

    /**
     * Inserts a value.
     *
     * @param int $index The index where the value should be inserted.
     * @param mixed $value The value to insert.
     *
     * @return $this
     *
     * @throws \System\ArgumentOutOfRangeException $index is invalid.
     */
    public function insert($index, $value) {
        $len = $this->count();

        if (($index < 0) || ($index > $len)) {
            throw new \System\ArgumentOutOfRangeException('index', $index);
        }

        $newStr = \substr($this->getWrappedValue(), 0, $index) .
                  static::valueToString($value);

        if ($index < $len) {
            $newStr .= \substr($this->getWrappedValue(), $index);
        }

        return new static($newStr);
    }

    /**
     * Invokes the function for finding a string.
     *
     * @param string &$expr The expression to search for.
     * @param bool $ignoreCase Ignore case or not.
     * @param int $offset The offset.
     *
     * @return mixed The result of the invocation.
     */
    protected function invokeFindStringFunc(&$expr = null, $ignoreCase = false, $offset = 0) {
        $expr = static::valueToString($expr);
        $str = $this->getWrappedValue();
        $func = !$ignoreCase ? "\\strpos" : "\\stripos";

        return \call_user_func($func,
                               $str, $expr, $offset);
    }

    /**
     * Invokes a function for the current inner value of that object.
     * The current value is submitted as first argument of the function.
     *
     * @param callable $func The function to invoke.
     * @param mixed ...$arg One or more argument for $func.
     *
     * @return mixed The result of the invocation.
     */
    protected function invokeFuncForValue($func) {
        $args = \func_get_args();

        // remove $func
        array_splice($args, 0, 1);

        return $this->invokeFuncForValueArray($func, $args);
    }

    /**
     * Invokes a function for the current inner value of that object.
     * The current value is submitted as first argument of the function.
     *
     * @param callable $func The function to invoke.
     * @param array|\Traversable $args The arguments for $func.
     *
     * @return mixed The result of the invocation.
     */
    protected function invokeFuncForValueArray($func, $args = null) {
        if (null === $args) {
            $args = array();
        }

        if (!\is_array($args)) {
            $args = \iterator_to_array($args);
        }

        // current value as first argument
        array_unshift($args, $this->getWrappedValue());

        return \call_user_func_array($func, $args);
    }

    /**
     * Gets if the string is empty or not.
     *
     * @return bool Is empty or not.
     */
    public function isEmpty() {
        return $this->length() < 1;
    }

    /**
     * Gets if the string is NOT empty.
     *
     * @return bool Is empty (false) or not (true).
     */
    public function isNotEmpty() {
        return !$this->isEmpty();
    }

    /**
     * Checks if a string is (null) or empty.
     *
     * @param string $str The string to check.
     *
     * @return bool Is (null)/empty or not.
     */
    public static function isNullOrEmpty($str) {
        return (null === $str) ||
               ('' === static::valueToString($str));
    }

    /**
     * Checks if a string is (null) or contains whitespaces only.
     *
     * @param string $str The string to check.
     * @param string $charlist The custom list of chars that represent whitespaces (s. \\trim()).
     *
     * @return bool Is (null)/contains whitespaces only or not.
     */
    public static function isNullOrWhitespace($str, $charlist = null) {
        if (null === $str) {
            return true;
        }

        $args = array(static::valueToString($str));
        if (null !== $charlist) {
            $args[] = static::valueToString($charlist);
        }

        return '' === \call_user_func_array("\\trim", $args);
    }

    /**
     * Checks if a value is a string or a String object.
     *
     * @param mixed $val The value to check.
     *
     * @return bool Is string or not.
     */
    public static function isString($val) {
        return \is_string($val) ||
               ($val instanceof ClrString);
    }

    /**
     * Checks if that string contains whitespaces only.
     *
     * @param string $charlist The custom list of chars that represent whitespaces (s. \\trim()).
     *
     * @return bool Contains whitespaces only or not.
     */
    public function isWhitespace($charlist = null) {
        return static::isNullOrWhitespace($this->getWrappedValue(), $charlist);
    }

    /**
     * Joins items to one string.
     *
     * @param string $separator The separator between two items.
     * @param mixed $items The items to join.
     * @param string $defValue The default value to return if item list is empty
     *
     * @return string The joined string.
     */
    public static function join($separator, $items, $defValue = '') {
        return static::joinCallback(function() use ($separator) {
                                        return $separator;
                                    }, $items, $defValue);
    }

    /**
     * Joins items to one string.
     *
     * @param callable $separatorProvider The function that provides the separator between two items.
     * @param mixed $items The items to join.
     * @param string $defValue The default value to return if item list is empty
     *
     * @return string The joined string.
     */
    public static function joinCallback($separatorProvider, $items, $defValue = '') {
        if (!$items instanceof \System\Collections\IEnumerable) {
            $items = Enumerable::create($items);
        }

        return $items->aggregate(function($result, $x, $ctx) use ($separatorProvider) {
                                     if (!$ctx->isFirst) {
                                         // append separator

                                         $result .= ClrString::valueToString(\call_user_func($separatorProvider,
                                                                                             $x, $ctx));
                                     }
                                     else {
                                         $result = '';
                                     }

                                     return $result . ClrString::valueToString($x);
                                 }, $defValue);
    }

    /**
     * Finds the last occurrence of a string expression.
     *
     * @param string $expr The string to search for.
     * @param bool $ignoreCase Ignore case or not.
     * @param int $offset The offset.
     *
     * @return int The zero based index or -1 if not found.
     */
    public function lastIndexOf($expr, $ignoreCase = false, $offset = 0) {
        $func = !$ignoreCase ? "\\strrpos" : "\\strripos";

        $result = \call_user_func($func,
                                  $this->getWrappedValue(), static::valueToString($expr), $offset);

        return false !== $result ? $result
                                 : static::NOT_FOUND_INDEX;
    }

    /**
     * Finds the last occurrence of a char list.
     *
     * @param string $chars The list of chars.
     * @param bool $ignoreCase Ignore case or not.
     * @param int $offset The offset.
     *
     * @return int The zero based index or -1 if not found.
     */
    public function lastIndexOfAny($chars, $ignoreCase = false, $offset = 0) {
        $chars     = static::valueToString($chars);
        $charCount = \strlen($chars);

        $result = static::NOT_FOUND_INDEX;

        for ($i = 0; $i < $charCount; $i++) {
            $result = \max($result,
                           $this->lastIndexOf($chars[$i], $ignoreCase, $offset));
        }

        return $result;
    }

    /**
     * Gets the length of the string.
     *
     * @return int The length of that string.
     */
    public function length() {
        return $this->count();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($index) {
        return ($index >= 0) &&
               ($index < $this->length());
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($index) {
        $this->throwIfIndexOutOfRange($index);

        return $this->_wrappedValue[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($index, $value) {
        $this->throwIfIndexOutOfRange($index);

        $this->_wrappedValue[$index] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($index) {
        $this->throwIfIndexOutOfRange($index);

        $this->_wrappedValue[$index] = "\0";
    }

    /**
     * Formats a value for a formatted string.
     *
     * @param string $format The format string for $value.
     * @param mixed $value The value to parse.
     *
     * @return mixed The parsed value.
     */
    public static function parseFormatStringValue($format, $value) {
        if (null !== $format) {
            $format = static::valueToString($format);

            if ($value instanceof \DateTimeInterface) {
                return $value->format($format);
            }
            else if ($value instanceof \DateInterval) {
                return $value->format($format);
            }

            // default
            return \sprintf($format, $value);
        }

        return $value;
    }

    /**
     * Removes a part from the current string.
     *
     * @param int $startIndex The zero based start index.
     * @param int $length The length.
     *
     * @return $this
     *
     * @throws \System\ArgumentOutOfRangeException $startIndex or the combination of $startIndex and $length
     *                                             are invalid.
     */
    public function remove($startIndex, $length) {
        $curLen = $this->count();

        if (($startIndex < 0) || ($startIndex > $curLen)) {
            throw new \System\ArgumentOutOfRangeException('startIndex', $curLen);
        }

        $endIndex = $startIndex + $length;
        if ($endIndex > $curLen) {
            throw new \System\ArgumentOutOfRangeException('length', $endIndex);
        }

        $newStr = \substr($this->getWrappedValue(), 0, $startIndex);
        if ($endIndex < $curLen) {
            $newStr .= \substr($this->getWrappedValue(), $endIndex);
        }

        return new static($newStr);
    }

    /**
     * Replaces one or more expressions in that string.
     *
     * @param string $oldValue The value to search for.
     * @param string $newValue The new value.
     * @param bool $ignoreCase Ignore case or not.
     * @param int &$count The variable where to write how many expressions were replaced.
     *
     * @return $this
     */
    public function replace($oldValue, $newValue, $ignoreCase = false, &$count = null) {
        $func = !$ignoreCase ? "\\str_replace" : "\\str_ireplace";

        $newStr = \call_user_func_array($func,
                                        array($oldValue,
                                              static::valueToString($newValue),
                                              $this->getWrappedValue(),
                                              &$count));

        return new static($newStr);
    }

    /**
     * Replaces parts of that string by using a regular expression (s. \preg_replace()).
     *
     * @param mixed $pattern The regular expression.
     * @param mixed $replacement The replacement.
     *
     * @return $this
     */
    public function replaceRegExp($pattern, $replacement) {
        $newStr = \preg_replace($pattern, $replacement, $this->getWrappedValue());

        return new static($newStr);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize() {
        return $this->getWrappedValue();
    }

    /**
     * Checks if that strings starts with a specific expression.
     *
     * @param string $expr The expression to check.
     * @param bool $ignoreCase Ignore case or not.
     *
     * @return bool Starts with expression or not.
     */
    public function startsWith($expr, $ignoreCase = false) {
        return 0 === $this->invokeFindStringFunc($expr, $ignoreCase);
    }

    /**
     * Throws an exception if an index value is out of range.
     *
     * @param int $index The value to check.
     *
     * @throws ArgumentOutOfRangeException
     */
    protected function throwIfIndexOutOfRange($index) {
        if (($index < 0) || ($index >= $this->length())) {
            throw new ArgumentOutOfRangeException('index');
        }
    }

    /**
     * Converts that string to an array of its chars.
     *
     * @return array The string as char array.
     */
    public function toCharArray() {
        return $this->getIterator()
                    ->toArray();
    }

    /**
     * Converts that string to lower chars.
     *
     * @return static
     */
    public function toLower() {
        return new static($this->invokeFuncForValue("\\strtolower"));
    }

    /**
     * Converts that string to upper chars.
     *
     * @return static
     */
    public function toUpper() {
        return new static($this->invokeFuncForValue("\\strtoupper"));
    }

    /**
     * Trims that string at the beginning and the end.
     *
     * @param string $charlist The list of chars that represents whitespaces.
     *
     * @return static
     */
    public function trim($charlist = null) {
        return $this->trimMe("\\trim", $charlist);
    }

    /**
     * Trims that string at the end.
     *
     * @param string $charlist The list of chars that represents whitespaces.
     *
     * @return static
     */
    public function trimEnd($charlist = null) {
        return $this->trimMe("\\rtrim", $charlist);
    }

    /**
     * Trims that string.
     *
     * @param callable $func The function to use.
     * @param string $charlist The list of chars that represents whitespaces.
     *
     * @return static
     */
    protected function trimMe($func, $charlist) {
        $args = array();
        if (null !== $charlist) {
            $args[] = static::valueToString($charlist);
        }

        return new static($this->invokeFuncForValueArray($func, $args));
    }

    /**
     * Trims that string at the beginning.
     *
     * @param string $charlist The list of chars that represents whitespaces.
     *
     * @return static
     */
    public function trimStart($charlist = null) {
        return $this->trimMe("\\ltrim", $charlist);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized) {
        $this->__construct($serialized);
    }

    /**
     * Converts a value to a string.
     *
     * @param mixed $value The input value.
     *
     * @return string The output value.
     */
    public static function valueToString($value) {
        return \strval($value);
    }
}
