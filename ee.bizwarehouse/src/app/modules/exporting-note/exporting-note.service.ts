import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {catchError, map} from 'rxjs/operators';
import {Observable} from 'rxjs/Observable';
import {ExportingNoteMeta} from './exporting-note.meta';
import { AbstractCRUDService, DataResponse, TitleService } from '../../core';

@Injectable()
export class ExportingNoteService extends AbstractCRUDService<ExportingNoteMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Phiếu xuất kho', 'exporting_notes');
  }

  printBill(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/print`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  approve(id: number): Observable<ExportingNoteMeta> {
    return this.http.post<DataResponse<ExportingNoteMeta>>(`${this.urlRestAPI}/${id}/approve`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  disapprove(id: number): Observable<ExportingNoteMeta> {
    return this.http.post<DataResponse<ExportingNoteMeta>>(`${this.urlRestAPI}/${id}/disapprove`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
