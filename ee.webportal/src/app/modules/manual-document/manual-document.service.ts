import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import 'rxjs/add/operator/map';
import {Observable} from 'rxjs';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {DataResponse} from '../../core/common/data-response.metadata';
import {ManualDocumentMeta} from './manual-document.meta';

@Injectable()
export class ManualDocumentService extends AbstractCRUDService<ManualDocumentMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Hướng dẫn', 'system_manual_documents');
  }

  get(): Observable<ManualDocumentMeta> {
    return this.toPipe(this.http.get<DataResponse<ManualDocumentMeta>>(this.urlRestAPI));
  }
}
