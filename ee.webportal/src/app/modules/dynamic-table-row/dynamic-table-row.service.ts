import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class DynamicTableRowService extends AbstractCRUDService<any> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Hàng', 'dynamic_table_rows');
  }

  clearCachePage(): Observable<any> {
    const parameters: HttpParams = new HttpParams();
    return this.http.get<any>('clear_cache_page', {params: parameters}).catch(err => this.handleErrorRequest(err));
  }
}
