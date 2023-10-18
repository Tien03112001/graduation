import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {EmbedCodeMeta} from './embed-code.meta';

@Injectable()
export class EmbedCodeService extends AbstractCRUDService<EmbedCodeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Code nhúng', 'embed_scripts');
  }

}
