<div class="page users-page" id="users">
    <div class="section-card">

        <div class="section-header">
            <div>
                <h2>Community Users</h2>
                <p>Manage registered community users.</p>
            </div>

            <button class="primary-btn" id="add-new-user-btn">+ Add User</button>
        </div>

        <div class="user-tools">
            <input
                type="search"
                placeholder="Search user..."
                id="userSearch"
            >
        </div>

        <div class="table-container">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact_number }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->status }}</td>

                            <td>
                                <button
                                    type="button"
                                    class="user-view-btn"
                                    data-id="{{ $user->id }}"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>


<!-- =====================================================
     USER DETAILS / EDIT MODAL
===================================================== -->

<div
    id="userDetailsModal"
    class="user-details-modal"
    style="display:none;"
>

    <div class="user-details-modal-content">

        <div class="modal-header">
            <div>
                <h2>User Details</h2>
                <p>View and update community user information.</p>
            </div>

            <button
                type="button"
                id="closeUserDetailsModal"
                class="close-modal-btn"
            >
                &times;
            </button>
        </div>


        <form id="userDetailsForm">

            @csrf

            <input
                type="hidden"
                id="editUserId"
                name="id"
            >


            <div class="form-row">

                <div class="form-group">
                    <label for="editUserName">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="editUserName"
                        name="name"
                        required
                    >
                </div>


                <div class="form-group">
                    <label for="editUserEmail">
                        Email
                    </label>

                    <input
                        type="email"
                        id="editUserEmail"
                        name="email"
                        required
                    >
                </div>

            </div>


            <div class="form-row">

                <div class="form-group">
                    <label for="editUserContact">
                        Contact Number
                    </label>

                    <input
                        type="text"
                        id="editUserContact"
                        name="contact_number"
                    >
                </div>


                <div class="form-group">
                    <label for="editUserRole">
                        Role
                    </label>

                    <select
                        id="editUserRole"
                        name="role"
                    >
                        <option value="LOCAL">Local</option>
                        <option value="ADMIN">Admin</option>
                    </select>
                </div>

            </div>


            <div class="form-group">
                <label for="editUserAddress">
                    Address
                </label>

                <textarea
                    id="editUserAddress"
                    name="address"
                    rows="3"
                ></textarea>
            </div>


            <div class="form-group">
                <label for="editUserStatus">
                    Status
                </label>

                <select
                    id="editUserStatus"
                    name="status"
                >
                    <option value="ACTIVE">Active</option>
                    <option value="SUSPENDED">Suspended</option>
                </select>
            </div>

            <div class="form-group" id="adminPasswordGroup" style="display:none;">

                <label for="editUserPassword">Admin Password</label>

                <div class="password-input-wrapper">

                    <input
                        type="password"
                        id="editUserPassword"
                        name="password"
                        placeholder="Leave blank to keep current password"
                        autocomplete="new-password"
                    >

                    <label class="show-password-label">
                        <input
                            type="checkbox"
                            id="showUserPassword"
                        >
                        Show password
                    </label>

                </div>

                <small>
                    Password can only be changed for administrator accounts.
                </small>

            </div>


            <div class="modal-actions">

                <button
                    type="button"
                    id="cancelUserDetails"
                    class="secondary-btn"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="primary-btn"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

<div
    id="addUserModal"
    class="user-details-modal"
    style="display:none;"
>
    <div class="user-details-modal-content">

        <div class="modal-header">

            <h2>Add User</h2>

            <button
                type="button"
                id="closeAddUserModal"
                class="close-modal-btn"
            >
                &times;
            </button>

        </div>

        <form id="addUserForm">

            @csrf

            <div class="form-group">

                <label for="addUserName">
                    Full Name
                </label>

                <input
                    type="text"
                    id="addUserName"
                    name="name"
                    placeholder="Enter full name"
                    required
                >

            </div>

            <div class="form-group">

                <label for="addUserEmail">
                    Email
                </label>

                <input
                    type="email"
                    id="addUserEmail"
                    name="email"
                    placeholder="Enter email address"
                    required
                >

            </div>

            <div class="form-group">

                <label for="addUserContact">
                    Contact Number
                </label>

                <input
                    type="text"
                    id="addUserContact"
                    name="contact_number"
                    placeholder="Enter contact number"
                >

            </div>

            <div class="form-group">

                <label for="addUserAddress">
                    Address
                </label>

                <textarea
                    id="addUserAddress"
                    name="address"
                    placeholder="Enter address"
                ></textarea>

            </div>

            <div class="form-group">

                <label for="addUserRole">
                    Role
                </label>

                <select
                    id="addUserRole"
                    name="role"
                    required
                >
                    <option value="LOCAL">
                        Local
                    </option>

                    <option value="ADMIN">
                        Admin
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label for="addUserStatus">
                    Status
                </label>

                <select
                    id="addUserStatus"
                    name="status"
                    required
                >
                    <option value="ACTIVE">
                        Active
                    </option>

                    <option value="SUSPENDED">
                        Suspended
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label for="addUserPassword">
                    Password
                </label>

                <div class="password-input-wrapper">

                    <input
                        type="password"
                        id="addUserPassword"
                        name="password"
                        placeholder="Enter password"
                        autocomplete="new-password"
                        required
                    >

                    <label class="show-password-label">

                        <input
                            type="checkbox"
                            id="showAddUserPassword"
                        >

                        Show password

                    </label>

                </div>

            </div>

            <div class="modal-actions">

                <button
                    type="button"
                    class="secondary-btn"
                    id="cancelAddUser"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="primary-btn"
                >
                    Add User
                </button>

            </div>

        </form>

    </div>
</div>

    <script src="{{ asset('js/adminUser.js')}}"></script>
