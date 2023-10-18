import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {SystemWebsiteMeta} from './system-website.meta';

@Injectable()
export class SystemWebsiteService extends AbstractCRUDService<SystemWebsiteMeta> {

  protected cachedWebsite: string;

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Trang web', 'system_websites');
  }

}
