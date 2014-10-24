@extends('layouts.base')

@section('content')
 <div class="content">

    <div class="container">

      <div class="row">

        <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

          <div class="portlet">

            <h2 class="portlet-title">
              <u>Form Validation</u>
            </h2>

            <div class="portlet-body">

              <form id="demo-validation" action="./components-validation.html" data-validate="parsley" class="form parsley-form">

                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" id="name" name="name" class="form-control parsley-validated" data-required="true">
                </div> <!-- /.form-group -->

                <div class="form-group">
                  <label for="textarea-input">Textarea</label>
                  <textarea data-required="true" data-minlength="5" name="textarea-input" id="textarea-input" cols="10" rows="2" class="form-control parsley-validated"></textarea>
                </div> <!-- /.form-group -->

                <div class="form-group">
                  <label for="validateSelect">Select</label>
                  <select id="validateSelect" name="validateSelect" class="form-control parsley-validated" data-required="true">
                    <option value="">Please Select</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
                </div> <!-- /.form-group -->

                <div class="form-group">
                  <label for="select-multi-input">Select (multiple)</label>
                  <select data-required="true" multiple="" id="select-multi-input" class="form-control parsley-validated">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                  </select>
                </div> <!-- /.form-group -->

                <div class="form-group">
                  <label>Checkbox (stacked)</label>

                  <div class="checkbox">
                    <label>
                    <input type="checkbox" name="checkbox-1" class="parsley-validated" data-mincheck="2">
                    Option 1
                    </label>
                  </div> <!-- /.checkbox -->

                  <div class="checkbox">
                    <label>
                    <input type="checkbox" name="checkbox-1" class="parsley-validated" data-mincheck="2">
                    Option 2
                    </label>
                  </div> <!-- /.checkbox -->

                </div> <!-- /.form-group -->


                <div class="form-group">
                  <label>Radio (stacked)</label>

                  <div class="radio">
                    <label>
                    <input type="radio" name="radio-1" class="parsley-validated" data-required="true">
                    Option 1
                    </label>
                  </div> <!-- /.radio -->

                  <div class="radio">
                    <label>
                    <input type="radio" name="radio-1" class="parsley-validated" data-required="true">
                    Option 2
                    </label>
                  </div> <!-- /.radio -->

                </div> <!-- /.form-group -->

                <div class="form-group">
                  <button type="submit" class="btn btn-danger">Validate</button>
                </div> <!-- /.form-group -->

              </form>

            </div> <!-- /.portlet-body -->

          </div> <!-- /.portlet -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->

    </div> <!-- /.container -->



  </div> <!-- .content -->

@stop