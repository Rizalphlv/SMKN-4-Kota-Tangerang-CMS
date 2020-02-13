<head>
    <script src="//cdn.ckeditor.com/4.13.1/full/ckeditor.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <form action="" id="post_submit" method="post">

        <input type="hidden" id="_token" value="{{ csrf_token() }}">
        <input type="text" name="title" id="title"/>
        <input type="text" readonly name="slug_preview" id="slug_preview"/>

        <select name="cat" id="cat">

        </select>

        <input type="checkbox" name="publish" id="publish"/> <label for="publish">Publish</label>

        <textarea id="editor" name="editor">

        </textarea>

        <button type="submit">Submit</button>
    </form>

    <script>
        $(document).ready(() => {
            $("#title").on('input', function() {
                $("#slug_preview").val(slug( $(this).val() ))
            })

            let res = CKEDITOR.replace('editor', {
                removeButtons: 'Image'
            })

            $("#post_submit").on('submit', function(e) {
                e.preventDefault()

                $.ajax({
                    url: "/admin/post",
                    method: "POST",
                    data: {
                        title: $("#title").val(),
                        content: res.getData(),
                        category_id: $("#cat").val(),
                        published: $("#publish").is(':checked') ? 1 : 0,
                        _token: $("#_token").val()
                    },
                    success: function(res) {
                        console.log(res)
                    },
                    error: function(rej) {
                        console.log(rej)
                    }
                })
            })

            $.ajax({
                url: "/api/categories",
                type: "GET",
                success: function(res) {
                    $("#cat").html("")
                    res.map((value, index) => {
                        $("#cat").append(`
                            <option value="${value.id}">${value.category}</option>
                        `)
                    })
                }
            })

            function slug(text) {
                return text.toString().toLowerCase()
                            .replace(/\s+/g, '-')           // Replace spaces with -
                            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                            .replace(/^-+/, '')             // Trim - from start of text
                            .replace(/-+$/, '');            // Trim - from end of text
            }
        })
    </script>
</body>