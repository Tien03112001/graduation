import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {VirtualInventoryService} from '../virtual-inventory.service';
import { AbstractCRUDComponent, TitleService } from '../../../core';

@Component({
  selector: 'app-virtual-inventory-list',
  templateUrl: './virtual-inventory-list.component.html',
  styleUrls: ['./virtual-inventory-list.component.css'],
  providers: [VirtualInventoryService],
})
export class VirtualInventoryListComponent extends AbstractCRUDComponent<any> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Kho ảo online';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge', backdrop: 'static', keyboard: false};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {backdrop: 'static', keyboard: false};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      status: new FormControl(null),
    });
  }

  initNewModel(): any {
    return null;
  }

  constructor(
    service: VirtualInventoryService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  load() {
    this.service.loadAll().subscribe(value => {
      this.list = value;
    });
  }
}
