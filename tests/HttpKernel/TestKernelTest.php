<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Tests\HttpKernel;

use Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class TestKernelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->kernel = $this->getMockBuilder(
            'Symfony\Cmf\Component\Testing\HttpKernel\TestKernel'
        )->disableOriginalConstructor()->getMockForAbstractClass();
        $this->mockBundle = $this->getMock(
            'Symfony\Component\HttpKernel\Bundle\BundleInterface'
        );
    }

    /**
     * @dataProvider bundleSetProvider
     */
    public function testBundleSetRequire(array $bundleSets, $count)
    {
        $this->kernel->init();
        $this->kernel->requireBundleSets($bundleSets);
        $bundles = $this->kernel->registerBundles();
        $this->assertCount($count, $bundles);
    }

    public function bundleSetProvider()
    {
        return array(
            array(array('default', 'phpcr_odm'), 6),
            array(array('default', 'doctrine_orm'), 5),
            array(array('default', 'doctrine_orm', 'phpcr_odm'), 6),
        );
    }

    public function testBundleAdd()
    {
        $this->kernel->addBundle($this->mockBundle);
        $this->kernel->addBundle($this->mockBundle);

        $this->assertCount(2, $this->kernel->registerBundles());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequireInvalidBundleSet()
    {
        $this->kernel->init();
        $this->kernel->requireBundleSet('foobar');
    }
}
