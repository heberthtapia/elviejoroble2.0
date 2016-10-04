      <table id="tabla" align="center">
          <thead>
            <tr>
              <th width="270">PRODUCTO</th>
              <th>CANT.</th>
              <th width="70">P. UNIT (Bs)</th>
              <th width="70">DESC.</th>
              <th width="70">BONIF.</th>
              <th width="90">SUBTOTAL (Bs)</th>
              <th id="oculto"></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot hidden="">
              <tr>
                  <th colspan="5">SUB-TOTAL:</th>
                  <th>
                      <input type="text" disabled="disabled" id="subTotal" name="subTotal" value="0" >Bs
                      <input type="hidden" id="subTotal" name="subTotal" value="0" >
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">DESCUENTO:</th>
                  <th>
                      <input type="text" id="descuento" name="descuento" autocomplete="off" onKeyUp="calculaDes();" > Bs
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">BONIFICACI&Oacute;N:</th>
                  <th>
                      <input type="text" id="bonificacion" name="bonificacion" autocomplete="off" onKeyUp="calculaDes();" > Bs
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">TOTAL:</th>
                  <th>
                      <input type="text" disabled="disabled" id="total" name="total" value="0" />Bs
                      <input type="hidden" id="total" name="total" value="0" />
                  </th>
              </tr>
          </tfoot>

      </table >