import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {BlockMeta} from '../block.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {BlockService} from '../block.service';
import {WidgetService} from '../../widget/widget.service';
import {WidgetMeta} from '../../widget/widget.meta';

@Component({
  selector: 'app-block-create',
  templateUrl: './block-create.component.html',
  styleUrls: ['./block-create.component.css'],
  providers: [BlockService, WidgetService]
})
export class BlockCreateComponent extends AbstractModalComponent<BlockMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      widget: new FormControl(null, Validators.required),
      widget_id: new FormControl(null, Validators.required),
    });
  }

  onFormChanged(): void {
    this.formGroup.controls['widget'].valueChanges.subscribe(((value: WidgetMeta[]) => {
      if (value) {
        this.formGroup.controls['widget_id'].setValue(value[0].id);
      }
    }));
  }

  loaded(): void {
  }


  constructor(
    service: BlockService,
    modal: BsModalRef,
    builder: FormBuilder,
    private widgetService: WidgetService
  ) {
    super(service, modal, builder);
  }

  loadWidgets(params: any) {
    return this.widgetService.loadByParams(params);
  }

  initFieldForm(): FieldForm[] {
    return [
      {
        label: 'Widget',
        type: 'select2',
        typeof: 'text',
        formControl: 'widget',
        placeHolder: 'Bắt buộc',
        data: [],
        loader: {
          functionName: 'loadWidgets',
          params: {}
        },
        config: {
          singleSelection: true,
          primaryKey: 'id',
          labelKey: 'name',
          searchBy: 'name',
          allowSearchFilter: true
        }
      },
    ];

  }

  create() {
    super.create();
  }
}
