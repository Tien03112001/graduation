import {NgModule} from '@angular/core';
import {InterceptorService} from '../core/services';
import {TitleService} from '../core/services';
import {HTTP_INTERCEPTORS} from '@angular/common/http';
import {GuardService} from '../core/services';
import {ObjectObservableService} from '../core/services';

@NgModule({
  imports: [],
  declarations: [],
  providers: [
    TitleService,
    ObjectObservableService,
    GuardService,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: InterceptorService,
      multi: true,
    },
  ],
  exports: []
})
export class ServicesModule {
}
