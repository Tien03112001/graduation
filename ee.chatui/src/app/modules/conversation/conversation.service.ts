import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService, TitleService} from '../../core';
import {ToasterService} from 'angular2-toaster';
import {ConversationMeta} from './conversation.meta';
import {environment} from '../../../environments/environment';

@Injectable()
export class ConversationService extends AbstractCRUDService<ConversationMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý tin nhắn', 'conversations');
    this.setApiUrl(environment.api_url);
  }

}
