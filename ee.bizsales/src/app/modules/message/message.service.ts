import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService, TitleService} from '../../core';
import {ToasterService} from 'angular2-toaster';
import {MessageMeta} from './message.meta';
import {environment} from '../../../environments/environment';

@Injectable()
export class MessageService extends AbstractCRUDService<MessageMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý tin nhắn', 'messages');
    this.setApiUrl(environment.chatapp_url);
  }

}
