<?php

namespace App\Form\CustomType;

use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\AlignField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\BackgroundColorField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\ColorField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\HeaderGroupField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\IndentField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\ListField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\ScriptField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BlockQuoteField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\CleanField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\FormulaField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\LinkField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\StrikeField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\UnderlineField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuillTextareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', QuillType::class, [
                'label' => false,
                'required' => false,
                'quill_extra_options' => [
                    'placeholder' => '',
                ],
                'quill_options' => [
                    QuillGroup::build(
                        new HeaderGroupField(2, 3, 4),
                    ),
                    QuillGroup::build(
                        new BoldField(),
                        new ItalicField(),
                        new FormulaField(),
                        new UnderlineField(),
                        new StrikeField(),
                        new ScriptField(ScriptField::SCRIPT_FIELD_OPTION_SUB),
                        new ScriptField(ScriptField::SCRIPT_FIELD_OPTION_SUPER),
                        // new EmojiField(),
                    ),
                    QuillGroup::build(
                        new BlockQuoteField(),
                        new LinkField(),
                        new CleanField()
                    ),
                    QuillGroup::build(
                        new ListField(),
                        new ListField(ListField::LIST_FIELD_OPTION_BULLET)
                    ),
                    QuillGroup::build(
                        new IndentField(IndentField::INDENT_FIELD_OPTION_MINUS),
                        new IndentField(),
                    ),
                    QuillGroup::build(
                        new AlignField(),
                    ),
                    QuillGroup::build(
                        new ColorField(),
                        new BackgroundColorField()
                    ),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'inherit_data' => true,
        ]);
    }
}
