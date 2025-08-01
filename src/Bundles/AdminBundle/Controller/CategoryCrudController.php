<?php

namespace App\Bundles\AdminBundle\Controller;

use App\Bundles\CategoryBundle\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Lista de %entity_label_plural%')
            ->setPageTitle('edit',
                fn(Category $category) =>
                sprintf('Editando Convidado: <b>%s</b>', $category->getName()))
            ->setEntityLabelInSingular('Categoria')
            ->setEntityLabelInPlural('Categorias')
            ->setHelp('edit','Tela usada para editar uma <b>Categoria</b>')
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
            TextField::new('name', 'Nome: ')
            ->setFormTypeOption('attr', ['placeholder' => 'Insira o nome da Categoria.'])
            ->setRequired(true),
        ];
    }

}
