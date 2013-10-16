<?php
namespace TWebTest\Model;

use TWeb\Model\PageContent;
use PHPUnit_Framework_TestCase;

class PageContentTest extends PHPUnit_Framework_TestCase
{
    public function testPageContentInitialState()
    {
        $page = new PageContent();

        $this->assertNull(
            $page->name,
            '"name" should initially be null'
        );
        $this->assertNull(
            $page->id,
            '"id" should initially be null'
        );
        $this->assertNull(
            $page->content,
            '"content" should initially be null'
        );
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $page = new PageContent();
        $data  = array('name' => 'some name',
                       'id'     => 123,
                       'content'  => 'some content');

        $page->exchangeArray($data);

        $this->assertSame(
            $data['name'],
            $page->name,
            '"name" was not set correctly'
        );
        $this->assertSame(
            $data['id'],
            $page->id,
            '"id" was not set correctly'
        );
        $this->assertSame(
            $data['content'],
            $page->content,
            '"content" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $page = new PageContent();

        $page->exchangeArray(array('name' => 'some name',
                                    'id'     => 123,
                                    'content'  => 'some content'));
        $page->exchangeArray(array());

        $this->assertNull(
            $page->name, '"name" should have defaulted to null'
        );
        $this->assertNull(
            $page->id, '"id" should have defaulted to null'
        );
        $this->assertNull(
            $page->content, '"content" should have defaulted to null'
        );
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $page = new PageContent();
        $data  = array('name' => 'some name',
                       'id'     => 123,
                       'content'  => 'some content');

        $page->exchangeArray($data);
        $copyArray = $page->getArrayCopy();

        $this->assertSame(
            $data['name'],
            $copyArray['name'],
            '"name" was not set correctly'
        );
        $this->assertSame(
            $data['id'],
            $copyArray['id'],
            '"id" was not set correctly'
        );
        $this->assertSame(
            $data['content'],
            $copyArray['content'],
            '"content" was not set correctly'
        );
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $page = new PageContent();

        $inputFilter = $page->getInputFilter();

        $this->assertSame(3, $inputFilter->count());
        $this->assertTrue($inputFilter->has('name'));
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('content'));
    }
}