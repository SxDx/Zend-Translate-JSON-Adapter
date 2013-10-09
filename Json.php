<?php
/**
 *
 * Copyright (c) 2012, René Koller
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 *
 * 1) Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 * 2) Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the
 *    distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

/** Zend_Locale */
require_once 'Zend/Locale.php';

/** Zend_Translate_Adapter */
require_once 'Zend/Translate/Adapter.php';


/**
 * @category   Zend
 * @package    Zend_Translate
 */
class Zend_Translate_Adapter_Json extends Zend_Translate_Adapter
{
    private $_data = array();

    public function _($messageId, $locale = NULL) {
        $translation = $this->translate($messageId, $this->getLocale());
        if(func_num_args() > 1) {
            $args = func_get_args();
            $print = $args[1];
            $print[0] = $translation;
            $translation = call_user_func_array('sprintf', $print);
        }
        return $translation;
    }

    /**
     * Flattens an array
     *
     * @return array
     */
    protected function _collapse($array)
    {
        $result = array();
        foreach ($array as $key => $val) {
            if ((array)$val === $val) {
                foreach ($this->_collapse($val) as $nested_key => $nested_val) {
                    $result[$key . '.' . $nested_key] = $nested_val;
                }
            } else {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
     * Load translation data
     *
     * @param  string|array  $data
     * @param  string        $locale  Locale/Language to add data for, identical with locale identifier,
     *                                see Zend_Locale for more information
     * @param  array         $options OPTIONAL Options to use
     * @return array
     */
    protected function _loadTranslationData($data, $locale, array $options = array())
    {
        $this->_data = array();
        if (!file_exists($data)) {
            require_once 'Zend/Translate/Exception.php';
            throw new Zend_Translate_Exception("Json Translation file '". $data ."' not found");
        }

        $jsonString = file_get_contents($data);
        $jsonString = preg_replace('/__(\d+)__/', '%$1$s', $jsonString);
        $array = json_decode($jsonString,true);

        if (!isset($this->_data[$locale])) {
            $this->_data[$locale] = array();
        }

        $this->_data[$locale] = $this->_collapse($array) + $this->_data[$locale];
        return $this->_data;
    }

    /**
     * returns the adapters name
     *
     * @return string
     */
    public function toString()
    {
        return "Json";
    }
}
