<?php

namespace App\Controller\Admin;

use App\Entity\OptionProduct;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class OptionProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OptionProduct::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('product', 'Product'),
            TextField::new('option.question.text', 'Question Text')
                ->setFormTypeOption('disabled', 'disabled'),
            AssociationField::new('option', 'Option'),
            BooleanField::new('isAvailable', 'Is Available'),
        ];
    }
}
