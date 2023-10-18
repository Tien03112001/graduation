import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {SupportTicketMeta} from './support-ticket.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class SupportTicketService extends AbstractCRUDService<SupportTicketMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Bài viết', 'system_support_tickets');
  }

  assignTags(id: number, ids: number[]): Observable<SupportTicketMeta> {
    return this.toPipe(this.http.post<DataResponse<SupportTicketMeta>>(`${this.urlRestAPI}/${id}/tags`, {ids: ids.join()}));
  }

  getMeta(id: number): Observable<SupportTicketMeta> {
    return this.toPipe(this.http.get<DataResponse<SupportTicketMeta>>(`${this.urlRestAPI}/${id}/meta`));
  }

  delete(item: any) {
    return this.toPipe(this.http.put<DataResponse<any>>(`delete_support`, {item: item}));
  }

  changeOrder(item) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${item['id']}/change_order`, item));
  }

  up(id: number): Observable<SupportTicketMeta> {
    return this.http.post<DataResponse<SupportTicketMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<SupportTicketMeta> {
    return this.http.post<DataResponse<SupportTicketMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  getSupportDetails(id: number): Observable<SupportTicketMeta> {
    return this.toPipe(this.http.get<DataResponse<SupportTicketMeta>>(`${this.urlRestAPI}/${id}`));
  }
}
