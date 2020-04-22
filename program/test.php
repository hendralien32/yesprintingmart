<script>
        $(function () {
            $("td").hover(function () {
                $el = $(this);
                $el.parent().addClass("hover");
                var tdIndex = $('tr').index($el.parent());
                if ($el.parent().has('td[rowspan]').length == 0) {
                    $el.parent().prevAll('tr:has(td[rowspan]):first')
                            .find('td[rowspan]').filter(function () {
                                return checkRowSpan(this, tdIndex);
                            }).addClass("hover");
                }
            }, function () {
                $el.parent()
        .removeClass("hover")
        .prevAll('tr:has(td[rowspan]):first')
        .find('td[rowspan]')
        .removeClass("hover");

            });
        });
        function checkRowSpan(element, pIndex) {
            var rowSpan = parseInt($(element).attr('rowspan'));
            var cIndex = $('tr').index($(element).parent());
            return rowSpan >= pIndex + 1 || (cIndex + rowSpan) > pIndex;
        }
</script>

<table>
        <tbody>
            <tr>
                <td rowspan="3">
                    Alphabet
                </td>
                <td rowspan="2">
                    a
                </td>
                <td>
                    b
                </td>
                <td>
                    c
                </td>
            </tr>
            <tr>
                <td>
                    d
                </td>
                <td>
                    e
                </td>
            </tr>
            <tr>
                <td>
                    f
                </td>
                <td>
                    g
                </td>
                <td>
                    h
                </td>
            </tr>
            <tr>
                <td rowspan="3">
                    Number
                </td>
                <td>
                    1
                </td>
                <td>
                    2
                </td>
                <td>
                    3
                </td>
            </tr>
            <tr>
                <td>
                    4
                </td>
                <td>
                    5
                </td>
                <td>
                    6
                </td>
            </tr>
            <tr>
                <td>
                    7
                </td>
                <td>
                    8
                </td>
                <td>
                    9
                </td>
            </tr>
            <tr>
                <td>
                    1
                </td>
                <td>
                    2
                </td>
                <td>
                    3
                </td>
                <td>
                    4
                </td>
            </tr>
            <tr>
                <td>
                    1
                </td>
                <td>
                    2
                </td>
                <td rowspan="2">
                    3
                </td>
                <td>
                    4
                </td>
            </tr>
            <tr>
                <td>
                    1
                </td>
                <td>
                    2
                </td>
                <td>
                    4
                </td>
            </tr>
        </tbody>
    </table>

https://stackoverflow.com/questions/46256631/pass-value-from-ajax-return-to-input-field