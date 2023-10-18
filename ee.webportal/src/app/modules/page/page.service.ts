import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PageMeta} from './page.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';

@Injectable()
export class PageService extends AbstractCRUDService<PageMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Sub Page', 'pages');
  }

  css(object: PageMeta): Observable<PageMeta> {
    return this.toPipe(this.http.put<DataResponse<PageMeta>>(`${this.urlRestAPI}/${object['id']}/css`, {css: object['css']}));
  }

  script(object: PageMeta): Observable<PageMeta> {
    return this.toPipe(this.http.put<DataResponse<PageMeta>>(`${this.urlRestAPI}/${object['id']}/script`, {script: object['script']}));
  }
}
