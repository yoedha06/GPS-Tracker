<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name"><br>

    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username"><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email"><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password"><br>

    <label for="role">Role:</label><br>
    <select id="role" name="role">
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select><br>

    <input type="submit" value="Submit">
</form>
