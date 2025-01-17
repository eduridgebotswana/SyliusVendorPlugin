<?php

declare(strict_types=1);

namespace Odiseo\SyliusVendorPlugin\Form\Type;

use Odiseo\SyliusVendorPlugin\Entity\VendorInterface;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class VendorType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name',
            ])
            ->add('slug', TextType::class, [
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.slug',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
            ])
            ->add('phone', TextType::class, [
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.phone',
            ])
            ->add('altPhone', TextType::class, [
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.alt_phone',
            ])
            ->add('lat', NumberType::class, [
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.lat',
            ])
            ->add('lon', NumberType::class, [
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.lon',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => VendorTranslationType::class,
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.translations',
            ])
            ->add('email', EmailType::class, [
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.email',
            ])
            ->add('logoFile', FileType::class, [
                 'label' => 'odiseo_sylius_vendor_plugin.form.vendor.logo',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.channels',
            ])
            ->add('extraEmails', CollectionType::class, [
                'label' => 'odiseo_sylius_vendor_plugin.form.vendor.extra_emails',
                'entry_type' => VendorEmailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form): array {
                /** @var VendorInterface|null $vendor */
                $vendor = $form->getData();

                if (!$vendor instanceof VendorInterface || null === $vendor->getId()) {
                    return array_merge($this->validationGroups, ['odiseo_logo_create']);
                }

                return $this->validationGroups;
            },
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_vendor';
    }
}
