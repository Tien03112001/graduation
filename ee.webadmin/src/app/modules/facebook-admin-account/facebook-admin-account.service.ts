import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {FacebookAdminAccountMeta} from './facebook-admin-account.meta';
import { environment } from '../../../environments/environment';
import { DataResponse } from '../../core/common';

@Injectable()
export class FacebookAdminAccountService extends AbstractCRUDService<FacebookAdminAccountMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'quản trị facebook', 'facebook_admin_accounts');
    this.setApiUrl(environment.chat_api_url);
  }

  addPage(response: any) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}`, response));
  }

}
