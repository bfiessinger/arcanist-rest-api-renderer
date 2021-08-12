<?php

namespace Suenerds\ArcanistRestApiRenderer\Tests\Unit;

use Mockery as m;
use Arcanist\AbstractWizard;
use Illuminate\Http\Request;
use Suenerds\ArcanistRestApiRenderer\WizardStep;
use Suenerds\ArcanistRestApiRenderer\Fields\Field;
use Suenerds\ArcanistRestApiRenderer\Tests\TestCase;

class WizardStepTest extends TestCase
{
    /** @test */
    public function it_transforms_data_for_display()
    {
        $step = new class extends WizardStep {
            public function fields(): array
            {
                return [
                    Field::make('editable')->displayUsing(fn ($value) => '::display::'),
                ];
            }
        };

        $wizard = m::mock(AbstractWizard::class);
        $wizard->allows('data')->with('editable', null)->andReturn('::editable::');

        $step->init($wizard, 1);

        $this->assertEquals('::display::', $step->viewData(new Request())['editable']);
    }
}
