<?php

namespace App\Bundles\AdminBundle\Controller;

use App\Bundles\GuestBundle\Entity\Guest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GuestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Guest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Lista de %entity_label_plural%')
            ->setPageTitle('edit',
                fn(Guest $guest) =>
                sprintf('Editando Convidado: <b>%s</b>', $guest->getName()))
            ->setEntityLabelInSingular('Convidado')
            ->setEntityLabelInPlural('Convidados')
            ->setHelp('edit','Tela usada para editar uma <b>Convidado</b>')
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
                ->setFormTypeOption('attr', ['placeholder' => 'Insira o nome do convidado.'])
                ->setRequired(true),

            BooleanField::new('is_confirmed', 'Presença confirmada? ')
                ->renderAsSwitch(false)
                ->hideOnDetail()
                ->hideOnForm(),

            BooleanField::new('is_confirmed', 'Presença confirmada? ')
                ->hideOnForm()
                ->setCssClass('flex-direction-row w-full')
                ->hideOnIndex(),
        ];
    }

}
