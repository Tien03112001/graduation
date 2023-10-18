import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {LabelActiveStatusComponent} from './label-active-status/label-active-status.component';
import {ButtonCopyComponent} from './button-copy/button-copy.component';
import {LabelTypeComponent} from './label-type/label-type.component';

@NgModule({
  imports: [
    CommonModule,
  ],
  declarations: [
    LabelActiveStatusComponent,
    ButtonCopyComponent,
    LabelTypeComponent
  ],
  entryComponents: [
    LabelActiveStatusComponent,
    ButtonCopyComponent,
    LabelTypeComponent
  ],
  exports: [
    LabelActiveStatusComponent,
    ButtonCopyComponent,
    LabelTypeComponent
  ]
})
export class DirectivesModule {
}
