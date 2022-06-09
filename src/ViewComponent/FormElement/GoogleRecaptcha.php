<?php

class Flink_ViewComponent_FormElement_GoogleRecaptcha extends Flink_ViewComponent_FormElement {

    private $key;
    private $secret;

    public function set_key(string $key): self {
        $this->key = $key;
        return $this;
    }

    public function get_key() {
        return $this->key;
    }

    public function set_secret(string $secret): self {
        $this->secret = $secret;
        return $this;
    }

    public function get_secret() {
        return $this->secret;
    }

    public function was_successful() {
        Flink_Assert::is_true($this->get_form()->is_submitted(), 'captcha success validation called before form submission');
        if (Flink_Connection::is_localhost()) return true;
        if ($this->is_empty()) return false;
                
        $data = array(
            'secret' => $this->get_secret(),
            'response' => $this->get_value()
        );
        $query = http_build_query($data);

        $options = array(
            'http' => array (
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 
                    "Content-Length: " . strlen($query) . "\r\n" .
                    "User-Agent:MyAgent/1.0\r\n",
                'method' => 'POST',
                'content' => $query
            )
        );

        $context = stream_context_create($options);
        $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

        return json_decode($verify)->success;
    }

    public function render() {
        Flink_Assert::not_null($this->key, 'recaptcha vc can not be rendered without a key');
        Flink_Assert::not_null($this->secret, 'recaptcha vc can not be rendered without a secret');
        parent::render();
    }

}