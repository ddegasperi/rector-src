<?php declare(strict_types=1);

namespace Rector\Tests\NodeAnalyzer;

use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Tests\AbstractContainerAwareTestCase;

final class ClassAnalyzerTest extends AbstractContainerAwareTestCase
{
    /**
     * @var ClassAnalyzer
     */
    private $classAnalyzer;

    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    protected function setUp(): void
    {
        $this->classAnalyzer = $this->container->get(ClassAnalyzer::class);
        $this->builderFactory = $this->container->get(BuilderFactory::class);
    }

    public function testTraitResolveTypeAndParentTypes(): void
    {
        $this->assertSame(
            ['SomeClass'],
            $this->classAnalyzer->resolveTypeAndParentTypes(new Class_('SomeClass'))
        );

        $classWithParent = $this->builderFactory->class('SomeClass')
            ->extend('ParentClass')
            ->getNode();

        $this->assertSame(
            ['SomeClass', 'ParentClass'],
            $this->classAnalyzer->resolveTypeAndParentTypes($classWithParent)
        );

        $this->assertSame(
            ['SomeInterface'],
            $this->classAnalyzer->resolveTypeAndParentTypes(new Interface_('SomeInterface'))
        );

        $this->assertSame(
            ['SomeTrait'],
            $this->classAnalyzer->resolveTypeAndParentTypes(new Trait_('SomeTrait'))
        );
    }
}
