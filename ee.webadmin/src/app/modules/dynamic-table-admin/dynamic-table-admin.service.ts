import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';

@Injectable()
export class DynamicTableAdminService extends AbstractCRUDService<any> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Bảng công ty', 'dynamic_tables');
  }

  deleteColumn(id: number, item: any): Observable<any> {
    return this.toPipe(this.http.put<DataResponse<any>>(`${this.urlRestAPI}/${id}/column`, {item: item}));
  }

}
