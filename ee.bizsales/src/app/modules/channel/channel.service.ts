import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, TitleService} from '../../core';
import { ChannelMeta } from './channel.meta';
import {environment} from '../../../environments/environment';

@Injectable()
export class ChannelService extends AbstractCRUDService<ChannelMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý kênh', 'channels');
    this.setApiUrl(environment.definition_api_url);
  }

}
