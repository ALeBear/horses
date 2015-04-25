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
            ->will($this->returnValue($this->getFixturesPath() . '/view/file_template.html.php'));
    }

    public function testAddVariable()
    {
        $this->fileTemplate->addVariable('foo', 'bar');
        $this->assertEquals(sprintf('Array
(
    [templateFile] => %s/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => bar
)
', $this->getFixturesPath()), $this->fileTemplate->getRendering());
        $this->fileTemplate->addVariable('foo', 'anothervalue');
        $this->assertEquals(sprintf('Array
(
    [templateFile] => %s/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => anothervalue
)
', $this->getFixturesPath()), $this->fileTemplate->getRendering());
    }

    public function testAddVariables()
    {
        $this->fileTemplate->addVariables(['foo' => 'bar', 'greu' => 'pfff']);
        $this->assertEquals(sprintf('Array
(
    [templateFile] => %s/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => bar
    [greu] => pfff
)
', $this->getFixturesPath()), $this->fileTemplate->getRendering());
        $this->fileTemplate->addVariable('foo', 'anothervalue');
        $this->assertEquals(sprintf('Array
(
    [templateFile] => %s/view/file_template.html.php
    [renderedParts] => Array
        (
        )

    [foo] => anothervalue
    [greu] => pfff
)
', $this->getFixturesPath()), $this->fileTemplate->getRendering());
    }

    public function testAddPart()
    {
        $part = $this->getBasicMock('\horses\responder\view\View');
        $part->expects($this->any())
            ->method('getRendering')
            ->will($this->returnValue('BAR'));
        $part->expects($this->any())
            ->method('addVariables')
            ->will($this->returnSelf());

        $this->fileTemplate->addPart('foo', $part);
        $this->assertEquals(sprintf('Array
(
    [templateFile] => %s/view/file_template.html.php
    [renderedParts] => Array
        (
            [foo] => BAR
        )

    [partName] => foo
    [foo] => BAR
)
', $this->getFixturesPath()), $this->fileTemplate->getRendering());
    }

    /**
     * @expectedException \horses\responder\view\TemplateNotFoundException
     */
    public function testNoTemplateFound()
    {
        $this->fileTemplate = $this->getMockForAbstractClass('\horses\responder\view\FileTemplate');
        $this->fileTemplate->expects($this->any())
            ->method('getTemplatePath')
            ->will($this->returnValue('/NOFILE'));
        $this->fileTemplate->getRendering();
    }
}
