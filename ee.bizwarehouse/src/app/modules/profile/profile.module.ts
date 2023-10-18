import {CUSTOM_ELEMENTS_SCHEMA, NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ProfileComponent} from './profile.component';

const routing: Routes = [
  {
    path: '',
    component: ProfileComponent
  }
]
@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    RouterModule.forChild(routing)
  ],
  declarations: [
    ProfileComponent,
  ],
  entryComponents: [],
  exports: [RouterModule],
  schemas: [
    CUSTOM_ELEMENTS_SCHEMA
  ]
})
export class ProfileModule {
}
