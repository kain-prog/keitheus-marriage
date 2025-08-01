<?php

namespace App\Bundles\AdminBundle\Controller;

use App\Bundles\ProductBundle\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addJsFile('mask/priceMask.js');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Lista de %entity_label_plural%')
            ->setPageTitle('edit',
                fn(Product $product) =>
                sprintf('Editando Produto: <b>%s</b>', $product->getName()))
            ->setEntityLabelInSingular('Produto')
            ->setEntityLabelInPlural('Produtos')
            ->setHelp('edit','Tela usada para editar um <b>Produto</b>')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->reorder(Crud::PAGE_NEW, [
                Action::SAVE_AND_RETURN,
                Action::SAVE_AND_ADD_ANOTHER,
                Action::INDEX,
            ])
            ->reorder(Crud::PAGE_EDIT, [
                Action::SAVE_AND_RETURN,
                Action::SAVE_AND_CONTINUE,
                Action::INDEX,
            ])
            ->reorder(Crud::PAGE_DETAIL, [
                Action::EDIT,
                Action::DELETE,
                Action::INDEX,
            ]);

        return $actions;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('categories', 'Categorias: ')
                ->setFormTypeOption('attr', ['placeholder' => 'Selecione a categoria que o produto se enquadra.'])
                ->hideOnIndex(),

            TextField::new('name', 'Nome: ')
                ->setFormTypeOption('attr', ['placeholder' => 'Insira o nome do Produto.'])
                ->setRequired(true),

            ImageField::new('thumbnail', 'Imagem: ')
                ->setUploadDir('public/uploads')
                ->setBasePath('uploads')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setRequired(false),

            MoneyField::new('price', 'Preço')
                ->setFormTypeOption('attr', ['placeholder' => '1.000,00'])
                ->setCurrency('BRL')
                ->setNumDecimals(2)
                ->setStoredAsCents(false)
                ->setRequired(true),

            TextField::new('shortDescription', 'Resumo: ')
                ->setFormTypeOption('attr', ['placeholder' => 'Breve resumo do Produto.'])
                ->setRequired(false),

            TextEditorField::new('description', 'Descrição: ')
                ->setFormTypeOption('attr', ['placeholder' => 'Descreva o Produto.'])
                ->setRequired(false),

        ];
    }

}
