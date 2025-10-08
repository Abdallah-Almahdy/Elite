import React from 'react';
import { Button } from "@/components/ui/button"
import POSInterface from "./POSInterface";
export default function Test({ user, sections }) {

    console.log(sections);

    return (
        <div>
            <POSInterface />
        </div>
    );
}

