import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {catchError, map} from 'rxjs/operators';
import { AbstractCRUDService, DataResponse, TitleService } from '../../core';

@Injectable()
export class ExportingNoteService extends AbstractCRUDService<any> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Phiếu xuất kho', 'exporting_notes');
  }

  printBill(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/print`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
