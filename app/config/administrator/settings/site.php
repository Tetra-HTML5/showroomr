
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
<?php

/**
 * The main site settings page
 */
return array(

    /**
     * Settings page title
     *
     * @type string
     */
    'title' => 'Administrator Settings',

    /**
     * The edit fields array
     *
     * @type array
     */
    'edit_fields' => array(
        'site_name' => array(
            'title' => 'Site Name',
            'type' => 'text',
            'limit' => 50,
        ),
    ),

    /**
     * The validation rules for the form, based on the Laravel validation class
     *
     * @type array
     */
    'rules' => array(
        'site_name' => 'required|max:50',
    ),

    /**
     * This is run prior to saving the JSON form data
     *
     * @type function
     * @param array		$data
     *
     * @return string (on error) / void (otherwise)
     */
    'before_save' => function(&$data)
        {
            $data['site_name'] = $data['site_name'] . ' - The Blurst Site Ever';
        },

    /**
     * The permission option is an authentication check that lets you define a closure that should return true if the current user
     * is allowed to view this settings page. Any "falsey" response will result in a 404.
     *
     * @type closure
     */
    'permission'=> function()
        {
            return true;
        },
);