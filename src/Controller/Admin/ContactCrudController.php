<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
                ->setEntityLabelInPlural('demandes de contacts')
                ->setEntityLabelInSingular('demande de contact')
                ->setPageTitle("index",'SymRecipe - Administration demande de contact')
                ->setPaginatorPageSize(10)
                ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
        TextField::new('fullName'),
        TextField::new('email')
            ->hideOnForm(),
        TextField::new('message')
                ->setFormType(CKEditorType::class)
                ->hideOnIndex(),    
        DateTimeField::new('createdAt')
            ->hideOnForm()
        ];
    }
    
}
