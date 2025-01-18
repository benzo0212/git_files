<?php

    echo <<<_END
        <html>
            <head>
                <title>Form Test</title>
            </head>
            <body>
                <form method="post" action="formtest.php" id='form1'><pre>
                    Loan amount     <input type="text" name="principal" autofocus='autofocus' placeholder='Amount in Rands'>
                    Number of years <input type="text" name="years" value="15" required='required'>
                    Interest Rate   <input type="text" name="interest" value="3" required='required'>
                    <textarea name="name" cols="24" rows="4" wrap="off">
                    This is the dafault text
                    </textarea>

                    Vanilla <input type="checkbox" name="ice[]" value="vanilla">
                    Chocolate <input type="checkbox" name="ice[]" value="chocolate">
                    Strawberry <input type="checkbox" name="ice[]" value="strawberry">

                    <label>8am-Noon <input type="radio" name="time" value="1"></label>
                    <label>Noon-4pm <input type="radio" name="time" value="2" checked="checked"></label>
                    <label>4pm-8pm <input type="radio" name="time" value="3"></label>

                    Vegetables
                    <select name="veg" size="5" multiple="multiple">
                        <option value="Peas">Peas</option>
                        <option value="Beans">Beans</option>
                        <option value="Carrots">Carrots</option>
                        <option value="Cabbage">Cabbage</option>
                        <option value="Broccoli">Broccoli</option>
                    </select>

                    Choose a color <input type='color' name='color'>

                    <input type='time' name='time' value='12:34'>

                    <input type="image" name="submit" src="IMG-20230328-WA0055.jpg">
                </pre></form>
            </body>
        </html>
    _END;
?>