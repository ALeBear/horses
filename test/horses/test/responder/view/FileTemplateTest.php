<?php

namespace horses\test\responder\view;

use horses\responder\view\FileTemplate;
use horses\test\AbstractTest;

class FileTemplateTest extends AbstractTest
{
    /** @var FileTemplate */
    protected $fileTemplate;

    
    protected function setUp()
    {
        parent::setUp();
        $this->fileTemplate = $this->getMockForAbstractClass('\horses\responder\view\FileTemplate');
        $this->fileTemplate->expects($this->any())
            ->method('getTemplatePath')
            ->will($this->returnValue(__DIR__ . '/../../../fixtures/view/file_template.html.php'));
    }

    public function testAddVariable()
    {
        $this->fileTemplate->addVariable('foo', 'bar');
        $this->assertEquals('Array
(
    [templateFile] => /vagrant/projects/horses/test/horses/test/responder/view/../../../fixtures/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => bar
)
', $this->fileTemplate->getRendering());
        $this->fileTemplate->addVariable('foo', 'anothervalue');
        $this->assertEquals('Array
(
    [templateFile] => /vagrant/projects/horses/test/horses/test/responder/view/../../../fixtures/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => anothervalue
)
', $this->fileTemplate->getRendering());
    }

    public function testAddVariables()
    {
        $this->fileTemplate->addVariables(['foo' => 'bar', 'greu' => 'pfff']);
        $this->assertEquals('Array
(
    [templateFile] => /vagrant/projects/horses/test/horses/test/responder/view/../../../fixtures/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => bar
    [greu] => pfff
)
', $this->fileTemplate->getRendering());
        $this->fileTemplate->addVariable('foo', 'anothervalue');
        $this->assertEquals('Array
(
    [templateFile] => /vagrant/projects/horses/test/horses/test/responder/view/../../../fixtures/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => anothervalue
    [greu] => pfff
)
', $this->fileTemplate->getRendering());
    }

    public function testAddPart()
    {

    }
}
