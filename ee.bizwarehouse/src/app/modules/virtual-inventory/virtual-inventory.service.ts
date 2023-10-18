import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import { AbstractCRUDService, TitleService } from '../../core';

@Injectable()
export class VirtualInventoryService extends AbstractCRUDService<any> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Kho ảo online', 'virtual_inventory');
  }

}
