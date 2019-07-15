<?php

namespace Tests;

use Convidera\WYSIWYG\Providers\WYSIWYGServiceProvider;
use PHPUnit\Framework\TestCase;

class DirectivesRegexTest extends TestCase
{
    protected function callReplaceKeyWithElement($expression, $fnName)
    {
        $class = new \ReflectionClass('Convidera\WYSIWYG\Providers\WYSIWYGServiceProvider');
        $this->method = $class->getMethod('replaceKeyWithElement');
        $this->method->setAccessible(true);

        $obj = new WYSIWYGServiceProvider(null);
        return $this->method->invokeArgs($obj, [ $expression, $fnName ]);
    }

    /**
     * @test
     */
    public function single_quotes_default()
    {
        $php = $this->callReplaceKeyWithElement("'key'", "textElement");
        $this->assertEquals("\$data->textElement('key')", $php);
    }

    /**
     * @test
     */
    public function single_quotes_with_variable()
    {
        $php = $this->callReplaceKeyWithElement("'key', \$var", "textElement");
        $this->assertEquals("\$var->textElement('key')", $php);
    }

    /**
     * @test
     */
    public function single_quotes_with_options()
    {
        $php = $this->callReplaceKeyWithElement("'key', [ 'options' => true ]", "textElement");
        $this->assertEquals("\$data->textElement('key'), [ 'options' => true ]", $php);
    }

    /**
     * @test
     */
    public function single_quotes_with_varibale_and_options()
    {
        $php = $this->callReplaceKeyWithElement("'key', \$var, [ 'options' => true ]", "textElement");
        $this->assertEquals("\$var->textElement('key'), [ 'options' => true ]", $php);
    }

    /**
     * @test
     */
    public function double_quotes_default()
    {
        $php = $this->callReplaceKeyWithElement('"key"', "mediaElement");
        $this->assertEquals("\$data->mediaElement('key')", $php);
    }

    /**
     * @test
     */
    public function double_quotes_with_variable()
    {
        $php = $this->callReplaceKeyWithElement('"key", $var', "mediaElement");
        $this->assertEquals("\$var->mediaElement('key')", $php);
    }

    /**
     * @test
     */
    public function double_quotes_with_options()
    {
        $php = $this->callReplaceKeyWithElement('"key", [ "options" => true ]', "mediaElement");
        $this->assertEquals("\$data->mediaElement('key'), [ \"options\" => true ]", $php);
    }

    /**
     * @test
     */
    public function double_quotes_with_varibale_and_options()
    {
        $php = $this->callReplaceKeyWithElement('"key", $var, [ "options" => true ]', "mediaElement");
        $this->assertEquals("\$var->mediaElement('key'), [ \"options\" => true ]", $php);
    }

    /**
     * @test
     */
    public function complex_variable_expression()
    {
        $php = $this->callReplaceKeyWithElement("'media', \$product->slides[0], ['additionalClasses' => 'Product-card__hero-image']", "mediaElement");
        $this->assertEquals("\$product->slides[0]->mediaElement('media'), ['additionalClasses' => 'Product-card__hero-image']", $php);
    }

    /**
     * @test
     */
    public function multipleAdditionalAttributes()
    {
        $php = $this->callReplaceKeyWithElement("'media', ['additionalAttributes' => 'data-event=\"scroll\" data-action=\"Scrolltiefe Sichtbarkeit Block 4\" data-label=\"Sichtbarkeit Block 4\"']", "mediaElement");
        $this->assertEquals("\$data->mediaElement('media'), ['additionalAttributes' => 'data-event=\"scroll\" data-action=\"Scrolltiefe Sichtbarkeit Block 4\" data-label=\"Sichtbarkeit Block 4\"']", $php);
    }
}
