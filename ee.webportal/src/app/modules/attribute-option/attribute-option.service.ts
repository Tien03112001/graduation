import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {AttributeOptionMeta} from './attribute-option.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';

@Injectable()
export class AttributeOptionService extends AbstractCRUDService<AttributeOptionMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'option', 'attribute_options');
  }

  delete(id: number): Observable<AttributeOptionMeta> {
    return this.toPipe(this.http.delete<DataResponse<AttributeOptionMeta>>(`attribute_options/${id}`));
  }
}
