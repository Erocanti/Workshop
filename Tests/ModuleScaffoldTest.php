<?php namespace Modules\Workshop\Tests;

use Modules\Core\Tests\BaseTestCase;
use Modules\Workshop\Scaffold\ModuleScaffold;
use Modules\Workshop\Scaffold\Generators\FilesGenerator;
use Modules\Workshop\Scaffold\Generators\EntityGenerator;
use Modules\Workshop\Scaffold\Generators\ValueObjectGenerator;

class ModuleScaffoldTest extends BaseTestCase
{
    /**
     * @var ModuleScaffold
     */
    protected $scaffold;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var string Path to the module under test
     */
    protected $testModulePath;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];
        $this->scaffold = $this->app['asgard.module.scaffold'];
        $this->testModulePath = base_path('Modules/TestingTestModule');
    }

    private function cleanUp()
    {
        $this->finder->deleteDirectory($this->testModulePath);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->testModulePath);
    }

    /** @test */
    public function it_should_generate_module_folder()
    {
        // Run
        $this->scaffold
            ->vendor('asgardcms')
            ->name('TestingTestModule')
            ->setEntityType('eloquent')
            ->withEntities(['Category'])
            ->withValueObjects([])
            ->scaffold();

        // Assert
        $this->assertTrue($this->finder->isDirectory($this->testModulePath));

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_eloquent_entities_with_translations()
    {
        // Run
        $this->scaffold
            ->vendor('asgardcms')
            ->name('TestingTestModule')
            ->setEntityType('Eloquent')
            ->withEntities(['Category', 'Post'])
            ->withValueObjects([])
            ->scaffold();

        // Assert
        $entities = $this->finder->allFiles($this->testModulePath . '/Entities');
        $this->assertCount(4, $entities);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_doctrine_entities_with_translations()
    {
        // Run
        $this->scaffold
            ->vendor('asgardcms')
            ->name('TestingTestModule')
            ->setEntityType('Doctrine')
            ->withEntities(['Category', 'Post'])
            ->withValueObjects([])
            ->scaffold();

        // Assert
        $entities = $this->finder->allFiles($this->testModulePath . '/Entities');
        $this->assertCount(4, $entities);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_translation_entities()
    {
        // Run
        $this->scaffold
            ->vendor('asgardcms')
            ->name('TestingTestModule')
            ->setEntityType('Doctrine')
            ->withEntities(['Category'])
            ->withValueObjects([])
            ->scaffold();

        // Assert
        $categoryEntity = $this->finder->isFile($this->testModulePath . '/Entities/Category.php');
        $categoryTranslationEntity = $this->finder->isFile($this->testModulePath . '/Entities/CategoryTranslation.php');
        $this->assertTrue($categoryEntity);
        $this->assertTrue($categoryTranslationEntity);

        $this->cleanUp();
    }
}