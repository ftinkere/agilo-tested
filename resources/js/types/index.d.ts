export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    role: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export interface Password {
    id: number;
    project: string;
    login: string;
    password: string;
    user_id: number;
    directory_id: number;
}

export interface Directory {
    id: number;
    name: string;
    parent_id: number;
    contents: Directory[];
    passwords: Password[];
}