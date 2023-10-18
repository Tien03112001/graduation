import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {CommonModule} from '@angular/common';
import {ReactiveFormsModule} from '@angular/forms';
import {ManualDocumentComponent} from './manual-document.component';

const routing: Routes = [
  {
    path: '',
    component: ManualDocumentComponent
  }
];

@NgModule({
  imports: [
    CommonModule,
    ReactiveFormsModule,
    RouterModule.forChild(routing)
  ],
  declarations: [
    ManualDocumentComponent,
  ],
})
export class ManualDocumentModule {
}
